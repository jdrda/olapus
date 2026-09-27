<a href="{{ route($moduleBasicRoute.'.edit', $result->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square" aria-hidden="true"></i> {{ trans('admin.edit') }}</a>
@if (\Illuminate\Support\Facades\Route::has($moduleBasicRoute.'.destroy'))
    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $result->id }}"><i class="bi bi-trash" aria-hidden="true"></i> {{ trans('admin.delete') }}</button>
@endif
