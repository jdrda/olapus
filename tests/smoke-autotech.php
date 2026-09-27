<?php
/** Dependency-free regression check. Query/Model/Request doubles are NOT a Laravel integration test. */
namespace Illuminate\Database\Eloquent {
    class Model {
        public function qualifyColumn($name) { return 'custom_table.'.$name; }
        public function getAdminFilters() { return []; }
        public function getAdminColumnFilters() { return $GLOBALS['definitions']; }
        public function getAdminSearchMode() { return 'phrase'; }
        public function getAdminFilterPresets() { return [['label'=>'Pending', 'filters'=>['completed'=>'0']]]; }
    }
    class Builder {
        public array $conditions = [];
        public function getModel() { return new Model; }
        public function where($column, $operator = null, $value = null) {
            if (is_callable($column)) {
                $group = new self; $column($group); $this->conditions[] = ['AND', $group->conditions];
            } else { $this->conditions[] = ['where', $column, $operator, $value]; }
            return $this;
        }
        public function orWhere($column, $operator, $value) { $this->conditions[]=['OR', $column, $operator, $value]; return $this; }
        public function whereDate($column, $operator, $value) { $this->conditions[]=['date', $column, $operator, $value]; return $this; }
        public function orWhereDate($column, $operator, $value) { $this->conditions[]=['OR_DATE', $column, $operator, $value]; return $this; }
        public function whereIn($column, $values) { $this->conditions[]=['IN', $column, $values]; return $this; }
        public function whereRaw($expression) { $this->conditions[]=['RAW', $expression]; return $this; }
    }
}
namespace Illuminate\Support\Facades {
    class Auth { public static ?int $id=3; public static function id() { return self::$id; } }
    class Cache {
        public static array $data=[];
        public static function get($key,$default=null) { return self::$data[$key]??$default; }
        public static function forever($key,$value) { self::$data[$key]=$value; }
    }
    class Request {}
}
namespace {
    use App\Support\ColumnFilters;
    use App\Support\AdminSearch;
    use App\Support\AdminFilters;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Cache;
    class QueryBag { public function __construct(public array $values) {} public function has($key) { return array_key_exists($key,$this->values); } }
    class FakeRequest {
        public QueryBag $query;
        public function __construct(public array $values) { $this->query=new QueryBag($values); }
        public function query($key=null,$default=null) { return $key===null ? $this->values : ($this->values[$key]??$default); }
        public function fullUrl() { return route('admin.project.index',$this->values); }
    }
    function request() { return $GLOBALS['request']; }
    function trans($key) { return $key; }
    function route($name,array $query=[]) { return 'https://test.invalid/'.$name.($query ? '?'.http_build_query($query,'','&',PHP_QUERY_RFC3986) : ''); }
    function abort($code,$message='') { throw new RuntimeException($message,$code); }
    function abort_unless($value,$code,$message='') { if (!$value) abort($code,$message); }
    function abort_if($value,$code,$message='') { if ($value) abort($code,$message); }
    foreach (['RelationFilters','ColumnFilters','AdminSearch','AdminFilters'] as $file) { require dirname(__DIR__).'/app/Support/'.$file.'.php'; }
    require dirname(__DIR__).'/app/Helpers.php';
    $checks=0;
    function check($test,$label) { global $checks; ++$checks; if (!$test) throw new RuntimeException($label); }
    function params($url) { parse_str(parse_url($url,PHP_URL_QUERY)??'', $out); return $out; }
    $definitions = [
        'completed'=>['type'=>'exact','value_type'=>'boolean','options'=>['0'=>'No','1'=>'Yes'],'label'=>'Completed'],
        'state'=>['type'=>'in','options'=>['new'=>'New','done'=>'Done'],'label'=>'State'],
        'name'=>['type'=>'like','label'=>'Name'],
        'prefix'=>['column'=>'name','type'=>'starts_with','label'=>'Prefix'],
        'day'=>['column'=>'created_at','type'=>'date','label'=>'Day'],
        'from'=>['column'=>'created_at','type'=>'date_from','label'=>'From'],
        'to'=>['column'=>'created_at','type'=>'date_to','label'=>'To'],
        'year'=>['type'=>'exact','value_type'=>'integer','min'=>2000,'max'=>2100,'label'=>'Year'],
        'cost'=>['type'=>'exact','value_type'=>'number','label'=>'Cost'],
    ];
    function run(array $query): array {
        $GLOBALS['request'] = new FakeRequest($query);
        $url=\App\Helpers::resetSaveIndexParameters('admin.project', ['search','search_mode','orderbycolumn','orderbytype','relation','filters'],['owner'=>[]], $GLOBALS['definitions']);
        if ($url===false) return $query;
        $query=params($url); $GLOBALS['request']=new FakeRequest($query);
        check(\App\Helpers::resetSaveIndexParameters('admin.project', ['search','search_mode','orderbycolumn','orderbytype','relation','filters'],['owner'=>[]],$GLOBALS['definitions'])===false,'Redirect loop');
        return $query;
    }
    check(ColumnFilters::normalize(['completed'=>'0','ghost'=>'x'],$definitions)===['completed'=>'0'],'Zero/allowlist');
    check(ColumnFilters::normalize(['state'=>['new','','done','new']],$definitions)===['state'=>['done','new']],'Multiple normalized');
    check(ColumnFilters::normalize(['completed'=>'','state'=>[]],$definitions)===[],'Empty clears');
    check(ColumnFilters::isDate('2024-02-29') && !ColumnFilters::isDate('2026-02-29'),'Real calendar');
    foreach ([['day'=>'2026-02-30'],['name'=>['nested']],['completed'=>'yes'],['state'=>['missing']],['year'=>'1999'],['year'=>'2026;DROP'],['cost'=>'1e200'],['state'=>[[]]],['name'=>str_repeat('x',501)]] as $bad) {
        try { ColumnFilters::normalize($bad,$definitions); check(false,'Invalid input accepted'); }
        catch (InvalidArgumentException $e) { check(true,'Invalid known value rejected'); }
    }
    $q=new Builder;
    ColumnFilters::apply($q, ['completed'=>'0','state'=>['new','done'],'from'=>'2026-09-01','to'=>'2026-09-30','name'=>'A','prefix'=>'B','day'=>'2026-09-22'], $definitions);
    check(count($q->conditions)===7,'Every column applied');
    check(in_array(['where','custom_table.completed','=','0'],$q->conditions,true),'Boolean zero predicate');
    check(in_array(['IN','custom_table.state',['done','new']],$q->conditions,true),'OR values via IN');
    check(in_array(['date','custom_table.created_at','>=','2026-09-01'],$q->conditions,true),'Lower date bound');
    check(in_array(['date','custom_table.created_at','<=','2026-09-30'],$q->conditions,true),'Upper date bound');
    check(in_array(['where','custom_table.name','LIKE','%A%'],$q->conditions,true),'Contains pattern');
    check(in_array(['where','custom_table.name','LIKE','B%'],$q->conditions,true),'Starts-with pattern');

    $fields=['name'=>['operator'=>'LIKE','prefix'=>'%','sufix'=>'%'],'description'=>['operator'=>'LIKE','prefix'=>'%','suffix'=>'%']];
    $q=new Builder; AdminSearch::apply($q,'Jan Praha',$fields);
    check($q->conditions===[['AND',[['OR','name','LIKE','%Jan Praha%'],['OR','description','LIKE','%Jan Praha%']]]],'Phrase/grouping');
    $q=new Builder; $q->where('tenant_id','=',7); AdminSearch::apply($q,'Jan  Praha',$fields,'all_words');
    check(count($q->conditions)===3 && $q->conditions[0]===['where','tenant_id','=',7],'Search retains existing constraints');
    check($q->conditions[1]===['AND',[['OR','name','LIKE','%Jan%'],['OR','description','LIKE','%Jan%']]],'AND term 1 / OR columns');
    check($q->conditions[2]===['AND',[['OR','name','LIKE','%Praha%'],['OR','description','LIKE','%Praha%']]],'AND term 2 / OR columns');
    $q=new Builder; AdminSearch::apply($q,'0',$fields); check(str_contains(json_encode($q->conditions),'%0%'),'Search zero');
    $q=new Builder; AdminSearch::apply($q,'2026-02-30',['created_at'=>['type'=>'date']]);
    check($q->conditions===[['AND',[['RAW','1 = 0']]]],'Invalid dates not sent to DB');
    $q=new Builder; AdminSearch::apply($q,'2024-02-29',['created_at'=>['type'=>'date']]);
    check($q->conditions===[['AND',[['OR_DATE','created_at','=','2024-02-29']]]],'Valid date predicate');
    try { AdminSearch::apply(new Builder,str_repeat('a ',21),$fields,'all_words'); check(false,'No word limit'); }
    catch (InvalidArgumentException $e) { check(true,'Word cap'); }

    $state=run(['filters'=>['completed'=>'0','state'=>['new','done']], 'relation'=>'owner:7', 'search'=>'Jan','search_mode'=>'all_words']);
    check($state['filters']['completed']==='0' && $state['search_mode']==='all_words','Nested state');
    check(run([])===$state,'Nested filters remembered');
    $state=run(['field_filter'=>'completed','field_value'=>'1','page'=>'4']);
    check($state['filters']['completed']==='1' && !isset($state['page']) && $state['relation']==='owner:7','One column changed');
    $state=run(['field_filter'=>'state','field_value'=>[]]); check(!isset($state['filters']['state']),'Clear multi column');
    $state=run(['filter'=>'owner','values'=>['8']]); check($state['filters']['completed']==='1' && $state['relation']==='owner:8','Relation form retains columns');
    $state=run(['filters'=>'']); check(!isset($state['filters']) && $state['relation']==='owner:8','Explicit clear columns');
    check(!isset(run([])['filters']),'Cleared columns not restored');
    $state=run(['search_mode'=>'phrase']); check(!isset($state['search_mode']),'Default search mode clears saved all_words');
    check(run(['reset_filters'=>'1'])===[],'Reset all');
    foreach ([['filters'=>'bad'],['filters'=>['completed'=>['bad']]],['field_filter'=>['bad']],['search_mode'=>['bad']],['search_mode'=>'unsafe'],['filters'=>['from'=>'no']]] as $bad) {
        try { run($bad); check(false,'Bad state accepted'); } catch (RuntimeException $e) { check($e->getCode()===400,'Bad state HTTP 400'); }
    }
    $state=run(['filters'=>['completed'=>'0'],'page'=>'3']); check($state['page']==='3','Pagination preserved');
    \Illuminate\Support\Facades\Auth::$id=4; check(run([])===[],'Cache isolated per user');
    \Illuminate\Support\Facades\Auth::$id=null; check(run([])===[],'No anonymous cache');

    $f=new AdminFilters(new Model,'admin.project',['filters'=>['state'=>['new','done'],'completed'=>'0'],'search'=>'Jan','search_mode'=>'all_words','page'=>3]);
    $hidden=$f->hiddenFields(); check(isset($hidden['filters[state][0]']) && $hidden['filters[completed]']==='0','Bracketed hidden fields');
    check(!isset($hidden['page']) && $hidden['search_mode']==='all_words','Hidden fields reset page/keep mode');
    check(!isset($f->hiddenFields(['search'])['search']),'Hidden exclusion');
    $controls=$f->columnControls(); check($controls['completed']['selected'] && $controls['completed']['options'][0]['selected'],'Zero displayed');
    $badges=$f->badges(); check(count($badges)===4,'All badges visible');
    check(params($badges[1]['url'])['filters']===['state'=>['done','new']],'Remove scalar badge only');
    check(params($badges[2]['url'])['filters']===['completed'=>'0','state'=>['new']],'Remove one multi value');
    $query=params($f->columnUrl(['state'=>''])); check($query['filters']===['completed'=>'0'] && $query['search']==='Jan','Preset merge');
    check(count($f->presets())===1 && params($f->presets()[0]['url'])['filters']['state']===['done','new'],'Preset preserves other fields');
    $one=new AdminFilters(new Model,'admin.project',['filters'=>['completed'=>'0']]);
    check(params($one->badges()[0]['url'])['filters']==='','Last badge explicitly clears cache');
    echo "OK: {$checks} Autotech checks (real support classes; request/query/model doubles).\n";
}
