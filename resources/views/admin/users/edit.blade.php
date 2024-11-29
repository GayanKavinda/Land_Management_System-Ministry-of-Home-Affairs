<!-- resources/views/admin/users/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ trans('messages.edit_user') }}</h2>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">{{ trans('messages.name') }} :</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
            </div>

            <div class="form-group">
                <label for="email">{{ trans('messages.email') }}:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
            </div>

        </form>
        <hr>

        <!-- Role Assignment Form -->
        <form action="{{ route('users.assign-role', $user) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="role">{{ trans('messages.assign_role_title') }} :</label>
                <select name="role" id="role" class="form-control">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">{{ trans('messages.assign_role_title') }}</button>
        </form>

        <hr>

        <!-- Role Removal Form -->
        <form action="{{ route('users.remove-role', $user) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="remove_role">{{ trans('messages.remove_role_title') }} :</label>
                <select name="remove_role" id="remove_role" class="form-control">
                    @foreach ($user->roles as $userRole)
                        <option value="{{ $userRole->name }}">{{ $userRole->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-danger">{{ trans('messages.remove_role_title') }}</button>
        </form>
    </div>
@endsection
