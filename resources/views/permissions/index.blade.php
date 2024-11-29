<!-- resources/views/permissions/index.blade.php -->
@extends('layouts.permissions-layout')

@section('content')
    <div class="container">    
        <h2>{{ trans('messages.user_types_permissions') }}</h2>

        <!-- Display success message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ trans('messages.success') . session('success') }}
            </div>
        @endif

        <!-- Display info message -->
        @if(session('info'))
            <div class="alert alert-info">
                {{ trans('messages.info') . session('info') }}
            </div>
        @endif

        <!-- Display warning message -->
        @if(session('warning'))
            <div class="alert alert-warning">
                {{ trans('messages.warning') . session('warning') }}
            </div>
        @endif

        <div class="text-end">
            <a href="{{ route('permissions.create') }}" class="btn btn-success mb-2">{{ trans('messages.create_permission') }}</a>
        </div>


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ trans('messages.user_type') }}</th>
                    <th>{{ trans('messages.assigned_permissions') }}</th>
                    <th>{{ trans('messages.select_permissions') }}</th>
                    <th>{{ trans('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @forelse ($role->permissions as $permission)
                                {{ $permission->name }}
                                @if (!$loop->last), @endif
                            @empty
                                {{ trans('messages.no_permissions_assigned') }}
                            @endforelse
                        </td>
                        <td>
                            <form action="{{ route('permissions.assign-to-role', $role) }}" method="POST">
                                @csrf
                                <div class="form-group">                                   
                                    @php
                                        $remainingPermissions = $allPermissions->diff($role->permissions);
                                    @endphp

                                    @forelse ($remainingPermissions as $permission)
                                        <div class="form-check">
                                            <input type="checkbox" name="permissions[]" id="permission{{ $permission->id }}" value="{{ $permission->id }}" class="form-check-input" 
                                                @if ($role->permissions->contains('id', $permission->id)) checked @endif>
                                            <label for="permission{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                                        </div>
                                    @empty
                                        <p>{{ trans('messages.no_permissions_available') }}</p>
                                    @endforelse
                                </div>
                                <button type="submit" class="btn btn-secondary">{{ trans('messages.assign_permissions') }}</button>
                            </form>

                            <!-- Display warning message for duplicate permissions -->
                            @if(session('duplicatePermissions') && session('duplicateRole') == $role->id)
                                <div class="alert alert-warning mt-2">
                                    {{ session('duplicatePermissions') }}
                                </div>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('permissions.show', $role->id) }}" class="btn btn-secondary">{{ trans('messages.view_assigned_permissions') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">{{ trans('messages.no_user_types_found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

     <!-- Script to show warning message without redirect -->
     <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Display warning message
            var warningMessage = document.getElementById('warningMessage');
            if (warningMessage) {
                alert('{{ trans('messages.warning') }}' + warningMessage.innerText);
            }
        });
    </script>
    
@endsection
