<?php
namespace Illuminate\Support\Facades {
    class Auth { public static ?int $id = 7; public static function id() { return self::$id; } }
    class Cache {
        public static array $values = [];
        public static function get($key, $default = null) { return self::$values[$key] ?? $default; }
        public static function forever($key, $value) { self::$values[$key] = $value; }
    }
    class Request { public static function input($name) { return \request()->query($name); } }
}
namespace {
    final class QueryBag { public function __construct(public array $values) {} public function has($key) { return array_key_exists($key, $this->values); } }
    final class TestRequest {
        public QueryBag $query;
        public function __construct(public array $values) { $this->query = new QueryBag($values); }
        public function query($key = null, $default = null) { return $key === null ? $this->values : ($this->values[$key] ?? $default); }
        public function fullUrl() { return route('admin.project.index', $this->values); }
    }
    function request() { return $GLOBALS['request']; }
    function route($name, array $parameters = []) { return 'https://test.invalid/admin/project'.($parameters ? '?'.http_build_query($parameters, '', '&', PHP_QUERY_RFC3986) : ''); }
    function abort($code, $message = '') { throw new \RuntimeException($message, $code); }
    function abort_if($condition, $code, $message = '') { if ($condition) abort($code, $message); }
    function abort_unless($condition, $code, $message = '') { if (!$condition) abort($code, $message); }
    require dirname(__DIR__).'/app/Support/ColumnFilters.php';
    require dirname(__DIR__).'/app/Support/RelationFilters.php';
    require dirname(__DIR__).'/app/Helpers.php';
    $count = 0;
    function check($value, $label) { global $count; $count++; if (!$value) throw new RuntimeException($label); }
    function run(array $values): array {
        $GLOBALS['request'] = new TestRequest($values);
        $url = \App\Helpers::resetSaveIndexParameters('admin.project', ['search', 'orderbycolumn', 'orderbytype', 'relation'], ['label'=>[], 'owner'=>[]]);
        if ($url === false) return $values;
        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
        $GLOBALS['request'] = new TestRequest($query);
        check(\App\Helpers::resetSaveIndexParameters('admin.project', ['search', 'orderbycolumn', 'orderbytype', 'relation'], ['label'=>[], 'owner'=>[]]) === false, 'Canonical redirect loops.');
        return $query;
    }
    check(run([]) === [], 'Empty request');
    check(run(['search'=>'0']) === ['search'=>'0'], 'Zero lost');
    check(run([]) === ['search'=>'0'], 'Search not remembered');
    check(run(['relation'=>'owner:2,label:3,label:1']) === ['relation'=>'label:1,label:3,owner:2','search'=>'0'], 'Canonical/filter state');
    check(run(['filter'=>'label','values'=>['4','5']]) === ['relation'=>'label:4,label:5,owner:2','search'=>'0'], 'Change group overwrote others');
    check(run(['filter'=>'label']) === ['relation'=>'owner:2','search'=>'0'], 'Group clear');
    check(run(['search'=>'']) === ['relation'=>'owner:2'], 'Clear search');
    check(run(['relation'=>'']) === [], 'Clear relation');
    check(run([]) === [], 'Cleared state resurrected');
    check(run(['relation'=>'label:1','page'=>'4','unexpected'=>'ignored']) === ['page'=>'4','relation'=>'label:1'], 'Pagination or whitelist');
    check(run(['filter'=>'owner','values'=>['2'],'page'=>'4']) === ['relation'=>'label:1,owner:2'], 'Filter did not reset page');
    check(run(['reset_filters'=>'1']) === [], 'Reset-all');
    check(run(['relation'=>'ghost:1,label:2']) === ['relation'=>'label:2'], 'Unknown key retained');
    foreach ([['search'=>['bad']], ['relation'=>['bad']], ['filter'=>'label','values'=>'bad'], ['filter'=>'label','values'=>[['bad']]], ['relation'=>'owner:2;drop']] as $values) {
        try { run($values); throw new RuntimeException('Malformed input accepted'); } catch (RuntimeException $exception) { check($exception->getCode() === 400, 'Expected HTTP400 for malformed input'); }
    }
    $state = array_values(\Illuminate\Support\Facades\Cache::$values)[0];
    check(!isset($state['unexpected']) && !isset($state['page']), 'Cache contains unexpected keys');
    \Illuminate\Support\Facades\Auth::$id = 8;
    check(run([]) === [], 'User isolation failed');
    check(run(['search'=>'another']) === ['search'=>'another'], 'Other user state');
    \Illuminate\Support\Facades\Auth::$id = 7;
    check(run([]) === ['relation'=>'label:2'], 'User isolation failed after switch');
    \Illuminate\Support\Facades\Auth::$id = null;
    check(run([]) === [], 'Anonymous request read shared state');
    echo "OK: {$count} checks of actual Helpers state with facade/request test doubles.\n";
}
