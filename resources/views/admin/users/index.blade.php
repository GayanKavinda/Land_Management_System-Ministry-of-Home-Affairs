@extends('layouts.permissions-layout')

@section('head')
    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
@endsection

@section('content')

    <div class="container">
        
        <h2>{{ trans('messages.users_title') }}</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ trans('messages.name') }}</th>
                    <th>{{ trans('messages.email') }}</th>
                    <th>{{ trans('messages.roles') }}</th>
                    <th>{{ trans('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="{{ $user->roles->isEmpty() ? 'no-role-user' : '' }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->roles->isEmpty())
                                <strong style="color: red;">Requested For User Role</strong>
                            @else
                                {{ implode(', ', $user->roles->pluck('name')->toArray()) }}
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('users.edit', $user) }}" class="btn">{{ trans('messages.edit_button') }}</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ trans('messages.delete_confirmation') }}')">{{ trans('messages.delete_button') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">{{ trans('messages.no_users_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>

    </div>
@endsection
