<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command;
use Tests\TestCase;

class MailTestCommandTest extends TestCase
{
    public function test_it_stops_before_connecting_when_smtp_configuration_is_incomplete(): void
    {
        config([
            'mail.default' => 'smtp',
            'mail.from.address' => 'sender@example.test',
            'mail.from.name' => 'Olapus',
            'mail.mailers.smtp' => [
                'transport' => 'smtp',
                'scheme' => null,
                'url' => null,
                'host' => '',
                'port' => '',
                'username' => null,
                'password' => null,
            ],
        ]);

        $exitCode = Artisan::call('mail:test', [
            'to' => 'recipient@example.test',
        ]);

        $output = Artisan::output();

        $this->assertSame(Command::FAILURE, $exitCode);
        $this->assertStringContainsString('Configuration: FAILED', $output);
        $this->assertStringContainsString('MAIL_HOST is empty.', $output);
        $this->assertStringContainsString('MAIL_PORT must be an integer', $output);
        $this->assertStringNotContainsString('Sending a real test message', $output);
    }

    public function test_it_can_complete_with_an_in_memory_mailer_without_external_connection(): void
    {
        config([
            'mail.default' => 'array',
            'mail.from.address' => 'sender@example.test',
            'mail.from.name' => 'Olapus',
            'mail.mailers.array' => [
                'transport' => 'array',
            ],
        ]);

        $exitCode = Artisan::call('mail:test', [
            'to' => 'recipient@example.test',
            '--mailer' => 'array',
        ]);

        $output = Artisan::output();

        $this->assertSame(Command::SUCCESS, $exitCode);
        $this->assertStringContainsString('Configuration: OK', $output);
        $this->assertStringContainsString('Connection test: skipped for [array] transport.', $output);
        $this->assertStringContainsString('Sending: OK', $output);
    }
}
