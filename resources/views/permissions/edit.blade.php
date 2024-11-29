<!-- resources/views/permissions/edit.blade.php -->
@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary">{{ trans('messages.back') }}</a>

    <h2>{{ trans('messages.edit_permission') }}</h2>

    <form action="{{ route('permissions.update', $permission) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">{{ trans('messages.permission_name') }}</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ trans('messages.update_permission') }}</button>
    </form>
@endsection
