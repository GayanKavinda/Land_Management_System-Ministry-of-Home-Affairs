<!-- resources/views/layouts/app.blade.php -->

@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/custom-content.css') }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

@section('content')
<div class="custom-container">
    <div class="row">
        <!-- Left Panel: Sidebar -->

        @if(auth()->user()->roles->isNotEmpty())
        <div class="col-md-3">
            <div class="card-left">
                <div class="card-body-left">
                    @php
                        $pendingRequests = \App\Models\UserRequest::where('status', 'pending')->count();
                    @endphp

                    @can('view-admin-users')
                        <a href="#" class="btn btn-left" onclick="loadContent('{{ route('users.index') }}')">
                            {{ trans('messages.manage_users') }}

                            @if($pendingRequests > 0)
                                <span class="badge notification-badge">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    @endcan

                    @can('manage-permissions')                      
                            <a href="#" class="btn btn-left" onclick="loadContent('{{ route('permissions.index') }}')">{{ trans('messages.manage_permissions') }} </a>
                    @endcan

                    @can('manage-estates')                       
                            <a href="#" class="btn btn-left" onclick="loadContent('{{ route('manageEstates') }}')">{{ trans('messages.manage_estates') }} </a>
                    @endcan

                    @can('view-estates')                        
                            <a href="#" class="btn btn-left" onclick="loadContent('{{ route('manageEstates') }}')">{{ trans('messages.view_estates') }} </a>
                    @endcan  

                    @can('view-vehicle')
                            <a href="#" class="btn btn-left" >{{ trans('messages.vehicle_management') }} </a>
                    @endcan

                    @can('view-bungalow')
                            <a href="#" class="btn btn-left" >{{ trans('messages.bungalow_booking') }} </a>
                    @endcan

                    @can('view-report')
                            <a href="{{ route('show.report') }}" class="btn btn-left">{{ trans('messages.generate_reports') }} </a>
                    @endcan

                    @can('view-activity-log')
                            <a href="#" class="btn btn-left" onclick="loadContent('{{ route('activity.logs') }}')"> {{ trans('messages.user_activity_logs') }} </a> 
                    @endcan

                </div>
            </div>
        </div>
        @endif
        <!-- Right Panel: Main Content -->
        <div class="col-md-9">
            <div class="card-right">
                <div class="card-body custom-content" id="main-content">
                    @can('show-system-desc')
                        <h1>{{ trans('messages.system_heading') }}</h1>
                        <p>{{ trans('messages.system_description') }}</p>
                    @endcan

                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        @if(auth()->user()->roles->isEmpty())
                            @php
                                // Check if there is a pending request for the current user
                                $userRequest = auth()->user()->userRequest;
                            @endphp

                            @if($userRequest && $userRequest->status === 'pending')
                                {{-- Display message if there is a pending request --}}
                                <p>Your request is pending. Please wait until it's approved.</p>
                            @else
                                {{-- Display message and button to request permissions --}}
                                <div>
                                    <p>You have to request to get the permissions to access the Land Management System.</p>
                                    <button class="btn btn-primary" onclick="requestPermissions()">Request Permissions</button>
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
   function loadContent(url) {
        // Make an AJAX request to fetch the content
        fetch(url)
            .then(response => response.text())
            .then(data => {
                // Update the main content with the fetched data
                document.getElementById('main-content').innerHTML = data;

            })
            .catch(error => console.error('Error:', error));
    }


    var userData = {
        user_id: {{ auth()->user()->id }},
        user_name: '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
    };

    function requestPermissions() {
        var formData = new FormData();
        formData.append('user_id', userData.user_id);
        formData.append('user_name', userData.user_name);
        formData.append('email', userData.email);

        fetch('{{ route('user.requests.store') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    // Handle validation errors
                    return response.json().then(data => {
                        throw new Error(data.error);
                    });
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error.message);
                // Display the error to the user, for example, using an alert or other UI component
                alert('Error: ' + error.message);
            });
    }

</script>
@endsection
