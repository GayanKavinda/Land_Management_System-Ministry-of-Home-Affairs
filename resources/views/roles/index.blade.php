<!-- resources/views/roles/index.blade.php -->

@extends('layouts.app')


@section('content')

<a href="{{ url()->previous() }}" class="btn btn-secondary">{{ trans('messages.back_button') }}</a>

    <h2>{{ trans('messages.title') }}</h2>

    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-2">{{ trans('messages.create_button') }}</a>

    @if(session('success'))
        <div class="alert alert-success">{{ trans('messages.success_message') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('messages.name') }}</th>
                <th>{{ trans('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">{{ trans('messages.edit_button') }}</a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ trans('messages.delete_confirmation') }}')">{{ trans('messages.delete_button') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
