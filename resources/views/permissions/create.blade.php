<!-- resources/views/permissions/create.blade.php -->
@extends('layouts.app')

@section('content')
    <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ trans('messages.back') }}</a>


    <div class="container">
    <h2>{{ trans('messages.create_permission') }}</h2>

    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">{{ trans('messages.permission_name') }}</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">{{ trans('messages.create_permission_button') }}</button>
    </form>

</div>
    

@endsection
