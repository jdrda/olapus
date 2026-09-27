@extends('admin.master')
@section('page-name', trans($moduleNameBlade.'.name'))
@section('page-icon', 'bi bi-kanban')
@section('menu-class-relations-example', 'menu-open')
@section('menu-class-project', 'active')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">{{ trans($moduleNameBlade.'.name') }}</h3></div>
    <div class="card-body">
        @include('admin.errors')
        <form method="post" action="{{ isset($results) ? route($moduleBasicRoute.'.update', $results->id) : route($moduleBasicRoute.'.store') }}">
            @csrf
            @isset($results) @method('PUT') @endisset
            <div class="mb-3">
                <label for="name" class="form-label">{{ trans($moduleNameBlade.'.fields.name') }}</label>
                <input id="name" name="name" class="form-control" value="{{ old('name', $results->name ?? '') }}" required maxlength="255">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="owner_id" class="form-label">{{ trans($moduleNameBlade.'.fields.owner') }}</label>
                    <select id="owner_id" name="owner_id" class="form-select" data-enhance="select">
                        <option value="">{{ trans('admin_examples.no_owner') }}</option>
                        @foreach (request('User', []) as $owner)
                            <option value="{{ $owner->id }}" @selected((string) old('owner_id', $results->owner_id ?? '') === (string) $owner->id)>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="label_ids" class="form-label">{{ trans($moduleNameBlade.'.fields.labels') }}</label>
                    <input type="hidden" name="_relations_present[label_ids]" value="1">
                    <select id="label_ids" name="label_ids[]" multiple class="form-select" data-enhance="select">
                        @foreach (request('ProjectLabel', []) as $label)
                            <option value="{{ $label->id }}" @selected(in_array($label->id, (array) old('label_ids', old('_relations_present.label_ids') ? [] : (isset($results) ? $results->labels->modelKeys() : []))))>{{ $label->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ trans($moduleNameBlade.'.fields.description') }}</label>
                <textarea id="description" name="description" rows="5" class="form-control" data-editor="html">{{ old('description', $results->description ?? '') }}</textarea>
            </div>

            {{-- Child records: a concrete example adapted from RecruitMe's repeatable results. --}}
            <input type="hidden" name="_tasks_present" value="1">
            <h4 class="h6">{{ trans($moduleNameBlade.'.fields.tasks') }}</h4>
            <div data-task-list>
                @foreach (old('tasks', old('_tasks_present') ? [] : (isset($results) ? $results->tasks->toArray() : [])) as $index => $task)
                    @include('admin.modules.project.task_row', ['index' => $index, 'task' => $task])
                @endforeach
            </div>
            <template id="project-task-template">
                @include('admin.modules.project.task_row', ['index' => '__INDEX__', 'task' => []])
            </template>
            <button type="button" class="btn btn-sm btn-outline-secondary mb-3" data-add-task>{{ trans('admin_examples.add_task') }}</button>
            <div class="text-end"><button class="btn btn-primary" type="submit">{{ trans('admin.save') }}</button></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
    @include('admin.modules.form_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const list = document.querySelector('[data-task-list]');
            const template = document.getElementById('project-task-template');
            if (!list || !template) return;
            let next = 0;
            list.querySelectorAll('[data-task-row]').forEach(row => {
                next = Math.max(next, Number(row.dataset.taskRow) + 1);
            });
            document.querySelector('[data-add-task]').addEventListener('click', function () {
                list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', String(next++)));
            });
            list.addEventListener('click', function (event) {
                const button = event.target.closest('[data-remove-task]');
                if (button) button.closest('[data-task-row]').remove();
            });
        });
    </script>
@endpush
