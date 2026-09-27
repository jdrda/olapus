@if (\Illuminate\Support\Facades\Route::has($moduleBasicRoute . '.destroy'))
    @foreach ($results as $result)
        <div class="modal fade" id="deleteModal{{ $result->id }}" tabindex="-1"
             aria-labelledby="deleteModalLabel{{ $result->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $result->id }}">{{ trans('admin.delete_row') }} {{ $result->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ trans('admin.close') }}"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">@yield('delete_confirmation_text') <strong>{{ $result->name ?? $result->headline ?? $result->id }}</strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('admin.close') }}</button>
                        <form action="{{ route($moduleBasicRoute . '.destroy', $result->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ trans('admin.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
