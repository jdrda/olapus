<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TestMail extends Command
{
    protected $signature = 'mail:test
        {to? : Recipient address; defaults to MAIL_FROM_ADDRESS}
        {--mailer= : Mailer to test; defaults to MAIL_MAILER}
        {--timeout=5 : SMTP connection timeout in seconds}';

    protected $description = 'Validate mail configuration, test the SMTP connection and send a test message';

    public function handle(): int
    {
        $mailer = trim((string) ($this->option('mailer') ?: config('mail.default')));
        $recipient = trim((string) ($this->argument('to') ?: config('mail.from.address')));
        $timeout = filter_var($this->option('timeout'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 60],
        ]);

        $this->newLine();
        $this->info('Olapus mail diagnostics');
        $this->line(str_repeat('-', 40));

        if (app()->configurationIsCached()) {
            $this->warn('Laravel configuration is cached. The test uses the currently cached values.');
        }

        if ($mailer === '') {
            return $this->failStage('Configuration', 'MAIL_MAILER is empty.');
        }

        if ($timeout === false) {
            return $this->failStage('Configuration', '--timeout must be an integer from 1 to 60 seconds.');
        }

        $mailerConfig = config("mail.mailers.{$mailer}");

        if (! is_array($mailerConfig)) {
            return $this->failStage('Configuration', "Mailer [{$mailer}] is not configured in config/mail.php.");
        }

        $fromAddress = trim((string) config('mail.from.address'));
        $fromName = trim((string) config('mail.from.name'));
        $transport = strtolower(trim((string) ($mailerConfig['transport'] ?? '')));

        $configurationErrors = [];

        if ($transport === '') {
            $configurationErrors[] = "Mailer [{$mailer}] has no transport configured.";
        }

        if (! filter_var($fromAddress, FILTER_VALIDATE_EMAIL)) {
            $configurationErrors[] = 'MAIL_FROM_ADDRESS is missing or is not a valid e-mail address.';
        }

        if (! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $configurationErrors[] = 'The recipient address is missing or is not a valid e-mail address.';
        }

        $smtp = null;

        if ($transport === 'smtp') {
            $smtp = $this->smtpSettings($mailerConfig);

            if ($smtp['host'] === '') {
                $configurationErrors[] = 'MAIL_HOST is empty.';
            }

            if ($smtp['port'] === null) {
                $configurationErrors[] = 'MAIL_PORT must be an integer from 1 to 65535.';
            }

            if (($smtp['username'] === '') xor ($smtp['password'] === '')) {
                $configurationErrors[] = 'MAIL_USERNAME and MAIL_PASSWORD must either both be set or both be empty.';
            }
        }

        $this->table(
            ['Setting', 'Value'],
            [
                ['Mailer', $mailer],
                ['Transport', $transport !== '' ? $transport : '(empty)'],
                ['From', $fromName !== '' ? "{$fromName} <{$fromAddress}>" : $fromAddress],
                ['Recipient', $recipient],
                ...($smtp !== null ? [
                    ['SMTP host', $smtp['host'] !== '' ? $smtp['host'] : '(empty)'],
                    ['SMTP port', $smtp['port'] ?? '(invalid / empty)'],
                    ['SMTP scheme', $smtp['scheme'] ?? '(auto)'],
                    ['SMTP username', $smtp['username'] !== '' ? '(configured)' : '(empty)'],
                    ['SMTP password', $smtp['password'] !== '' ? '(configured)' : '(empty)'],
                ] : []),
            ]
        );

        if ($configurationErrors !== []) {
            $this->error('Configuration: FAILED');

            foreach ($configurationErrors as $error) {
                $this->line("  - {$error}");
            }

            return self::FAILURE;
        }

        $this->info('Configuration: OK');

        if ($transport === 'smtp') {
            $connectionError = $this->testSmtpConnection($smtp, $timeout);

            if ($connectionError !== null) {
                return $this->failStage('SMTP connection', $connectionError);
            }

            $this->info('SMTP connection: OK');
        } else {
            $this->warn("Connection test: skipped for [{$transport}] transport.");
        }

        $this->line('Sending a real test message...');

        try {
            Mail::mailer($mailer)->raw(
                "Olapus mail test completed successfully.\n\n".
                'Mailer: '.$mailer."\n".
                'Transport: '.$transport."\n".
                'Time: '.now()->toDateTimeString(),
                function ($message) use ($recipient): void {
                    $message->to($recipient)
                        ->subject('Olapus mail test');
                }
            );
        } catch (Throwable $exception) {
            return $this->failStage(
                'Sending',
                $exception::class.': '.$exception->getMessage()
            );
        }

        $this->info("Sending: OK - test message sent to {$recipient}");
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Resolve effective SMTP settings, including MAIL_URL when configured.
     *
     * @return array{host:string,port:?int,scheme:?string,username:string,password:string}
     */
    private function smtpSettings(array $config): array
    {
        $url = trim((string) ($config['url'] ?? ''));
        $parts = $url !== '' ? parse_url($url) : false;

        $scheme = $parts !== false && isset($parts['scheme'])
            ? strtolower((string) $parts['scheme'])
            : $this->nullableString($config['scheme'] ?? null);

        $host = $parts !== false && isset($parts['host'])
            ? (string) $parts['host']
            : trim((string) ($config['host'] ?? ''));

        $rawPort = $parts !== false && isset($parts['port'])
            ? $parts['port']
            : ($config['port'] ?? null);

        $port = filter_var($rawPort, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 65535],
        ]);

        $username = $parts !== false && isset($parts['user'])
            ? rawurldecode((string) $parts['user'])
            : trim((string) ($config['username'] ?? ''));

        $password = $parts !== false && isset($parts['pass'])
            ? rawurldecode((string) $parts['pass'])
            : (string) ($config['password'] ?? '');

        return [
            'host' => $host,
            'port' => $port === false ? null : $port,
            'scheme' => $scheme,
            'username' => $username,
            'password' => $password,
        ];
    }

    /**
     * Open a separate socket so connection failures are reported before Mailer attempts a send.
     */
    private function testSmtpConnection(array $smtp, int $timeout): ?string
    {
        $host = $smtp['host'];
        $port = $smtp['port'];
        $scheme = $smtp['scheme'];

        $socketHost = filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
            ? "[{$host}]"
            : $host;

        $implicitTls = $scheme === 'smtps' || ($scheme === null && $port === 465);
        $remote = ($implicitTls ? 'ssl' : 'tcp')."://{$socketHost}:{$port}";

        $errno = 0;
        $error = '';

        $socket = @stream_socket_client(
            $remote,
            $errno,
            $error,
            $timeout,
            STREAM_CLIENT_CONNECT
        );

        if ($socket === false) {
            return "Cannot connect to {$host}:{$port} ({$errno}: {$error}).";
        }

        stream_set_timeout($socket, $timeout);
        $greeting = fgets($socket, 1024);
        $metadata = stream_get_meta_data($socket);
        fclose($socket);

        if ($greeting === false) {
            if (($metadata['timed_out'] ?? false) === true) {
                return "Connected to {$host}:{$port}, but the SMTP server greeting timed out.";
            }

            return "Connected to {$host}:{$port}, but no SMTP server greeting was received.";
        }

        $greeting = trim($greeting);

        if (! str_starts_with($greeting, '220')) {
            return "Server {$host}:{$port} responded, but not with an SMTP 220 greeting: {$greeting}";
        }

        return null;
    }

    private function nullableString(mixed $value): ?string
    {
        $value = strtolower(trim((string) $value));

        return in_array($value, ['', 'null', '(null)'], true) ? null : $value;
    }

    private function failStage(string $stage, string $message): int
    {
        $this->error("{$stage}: FAILED");
        $this->line('  '.$message);
        $this->newLine();

        return self::FAILURE;
    }
}
