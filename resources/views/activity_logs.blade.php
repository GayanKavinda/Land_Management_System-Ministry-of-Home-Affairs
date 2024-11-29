<!-- resources/views/activity_logs.blade.php -->
<head>
    <!-- Link to custom.css for styling -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pagination li {
            margin-right: 5px;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #ddd;
            color: #333;
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination .active a,
        .pagination .active span {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .pagination a:hover,
        .pagination span:hover {
            background-color: #ddd;
        }

        .pagination .disabled a,
        .pagination .disabled span {
            pointer-events: none;
            background-color: #f8f9fa;
            color: #6c757d;
            border-color: #ddd;
        }
</style>



</head>

<body>
    <div class="container">
        <h1>{{ __('messages.user_activity_logs') }}</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.id_log') }}</th>
                        <th>{{ __('messages.log_name_log') }}</th>
                        <th>{{ __('messages.description_log') }}</th>
                        <th>{{ __('messages.subject_log') }}</th>
                        <th>{{ __('messages.causer_log') }}</th>
                        <th>{{ __('messages.properties_log') }}</th>
                        <th>{{ __('messages.event_log') }}</th>
                        <th>{{ __('messages.timestamps_log') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activityLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->log_name }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->subject_id }} ({{ $log->subject_type }})</td>
                            <td>
                                {{ $log->causer_id }} (
                                {{ $log->causer_type === 'App\Models\User' ? $log->causer->email : __('messages.unknown_user_log') }}
                                )
                            </td>
                            <td>
                                <pre>{{ json_encode(json_decode($log->properties), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </td>
                            <td>{{ $log->event }}</td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                        {{ $activityLogs->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>

        </div>
    </div>
</body>