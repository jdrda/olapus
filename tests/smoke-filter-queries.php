<?php
namespace Illuminate\Database\Eloquent {
    class Builder {
        public array $conditions = [];
        public function whereHas($relationship, $callback) { $related = new self; $callback($related); $this->conditions[] = [$relationship, $related->conditions]; return $this; }
        public function where($column, $value) { $this->conditions[] = ['eq', $column, $value]; return $this; }
        public function whereIn($column, $values) { $this->conditions[] = ['in', $column, $values]; return $this; }
        public function visible() { $this->conditions[] = ['scope', 'visible']; return $this; }
        public function orderBy($column) { return $this; }
        public function pluck($label, $key) { return new \FakeCollection([1=>'Alpha',2=>'Beta',3=>'Gamma']); }
    }
    class Model {
        public function getQualifiedKeyName() { return 'custom_singular.id'; }
        public function qualifyColumn($name) { return 'custom_singular.'.$name; }
        public function newQuery() { return new Builder; }
    }
}
namespace Illuminate\Database\Eloquent\Relations {
    class Relation { public function getRelated() { return new \Illuminate\Database\Eloquent\Model; } }
}
namespace {
    class FakeCollection { public function __construct(private array $values) {} public function all() { return $this->values; } }
    class FakeModel extends \Illuminate\Database\Eloquent\Model {
        public function __construct(public string $match = 'any') {}
        public function getAdminFilters() { return ['label'=>['relation'=>'labels','label'=>'labels','match'=>$this->match], 'owner'=>['relation'=>'owner','label'=>'owner','scope'=>'visible']]; }
        public function getAdminColumnFilters() { return []; }
        public function getAdminSearchMode() { return 'phrase'; }
        public function getAdminFilterPresets() { return []; }
        public function labels() { return new \Illuminate\Database\Eloquent\Relations\Relation; }
        public function owner() { return new \Illuminate\Database\Eloquent\Relations\Relation; }
    }
    function trans($key) { return $key; }
    function route($name, array $query = []) { return 'https://test.invalid/'.$name.($query ? '?'.http_build_query($query,'','&',PHP_QUERY_RFC3986):''); }
    require dirname(__DIR__).'/app/Support/ColumnFilters.php';
    require dirname(__DIR__).'/app/Support/RelationFilters.php';
    require dirname(__DIR__).'/app/Support/AdminFilters.php';
    $checks = 0;
    function check($condition, $message) { global $checks; $checks++; if (!$condition) throw new RuntimeException($message); }
    function params($url) { parse_str(parse_url($url, PHP_URL_QUERY)??'', $query); return $query; }
    $filters = new \App\Support\AdminFilters(new FakeModel, 'admin.project', ['relation'=>'owner:3,label:1,label:2','search'=>'Alpha','orderbycolumn'=>'name','orderbytype'=>'asc','page'=>'9']);
    $builder = new \Illuminate\Database\Eloquent\Builder;
    $filters->apply($builder);
    check($builder->conditions === [
        ['labels', [['in','custom_singular.id',['1','2']]]],
        ['owner', [['scope','visible'],['in','custom_singular.id',['3']]]],
    ], 'Actual AdminFilters must group ANY values and AND relations, and use real related key');
    $controls = $filters->controls();
    check(count($controls) === 2, 'Controls count');
    check($controls['label']['options'][0]['selected'] && $controls['label']['options'][1]['selected'], 'Multiple selected options');
    check(params($controls['label']['clear_url'])['relation']==='owner:3','Group removal preserves owner');
    $badges = $filters->badges();
    check(count($badges)===4, 'Search plus 3 badges');
    check($badges[0]['label']==='admin.search: Alpha', 'Search badge');
    check(params($badges[1]['url'])['relation']==='label:2,owner:3','Single badge removal');
    check(params($filters->addUrl('label',1))['relation']==='label:1,label:2,owner:3','Add duplicate');
    check(params($filters->addUrl('label',3))['relation']==='label:1,label:2,label:3,owner:3','Add filter');
    check(!isset(params($filters->addUrl('label',3))['page']),'Page reset');
    check(params($filters->clearUrl())===['reset_filters'=>'1'],'Clear-all URL');
    $builder = new \Illuminate\Database\Eloquent\Builder;
    (new \App\Support\AdminFilters(new FakeModel('all'),'', ['relation'=>'label:1,label:2']))->apply($builder);
    check($builder->conditions === [['labels',[['eq','custom_singular.id','1']]],['labels',[['eq','custom_singular.id','2']]]], 'ALL mode');
    $missing = new \App\Support\AdminFilters(new FakeModel, 'admin.project', ['relation'=>'label:999']);
    check($missing->badges()[0]['label']==='labels: #999','Deleted selected ID badge');
    check($missing->controls()['label']['options'][3]===['id'=>'999','label'=>'#999','selected'=>true], 'Deleted selected ID removable option');
    check((new \App\Support\AdminFilters(new FakeModel,'', ['relation'=>'ghost:1']))->badges()===[], 'Unknown filter rejected by allowlist');
    echo "OK: {$checks} checks of actual AdminFilters with query/model test doubles.\n";
}
