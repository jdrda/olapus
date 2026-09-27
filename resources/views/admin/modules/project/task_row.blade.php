<div class="row g-2 align-items-end mb-2" data-task-row="{{ $index }}">
    <input type="hidden" name="tasks[{{ $index }}][id]" value="{{ $task['id'] ?? '' }}">
    <div class="col-sm-7">
        <label for="task-name-{{ $index }}" class="form-label">{{ trans('admin_module_projecttask.fields.name') }}</label>
        <input id="task-name-{{ $index }}" name="tasks[{{ $index }}][name]" class="form-control" value="{{ $task['name'] ?? '' }}" maxlength="255" required>
    </div>
    <div class="col-sm-3">
        <label for="task-completed-{{ $index }}" class="form-label">{{ trans('admin_module_projecttask.fields.completed') }}</label>
        <select id="task-completed-{{ $index }}" name="tasks[{{ $index }}][completed]" class="form-select">
            <option value="0" @selected(empty($task['completed']))>{{ trans('admin_examples.no') }}</option>
            <option value="1" @selected(!empty($task['completed']))>{{ trans('admin_examples.yes') }}</option>
        </select>
    </div>
    <div class="col-sm-2"><button type="button" class="btn btn-outline-danger" data-remove-task>{{ trans('admin_filters.remove') }}</button></div>
</div>
