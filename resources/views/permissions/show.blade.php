<!-- resources/views/permissions/show.blade.php -->
@extends('layouts.app')

@section('content')

<a href="{{ url()->previous() }}" class="btn btn-secondary">{{ trans('messages.back') }}</a>

    <div class="container">
        <h2>{{ trans('messages.permissions_for') }} {{ $role->name }}</h2>

        <a href="{{ route('permissions.index') }}" class="btn btn-secondary">{{ trans('messages.back') }}</a>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ trans('messages.name') }}</th>
                    <th>{{ trans('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('permissions.edit', $permission->id) }}" class="btn">{{ trans('messages.edit_button') }}</a>
                                <form action="{{ route('permissions.destroy', ['role' => $role, 'permission' => $permission->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ trans('messages.are_you_sure') }}')">{{ trans('messages.delete_button') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">{{ trans('messages.no_permissions_found_for', ['role' => $role->name]) }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
