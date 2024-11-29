<!-- manageEstates.blade.php -->

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  
</head>

<body>
    <div class="container">
        <h1>{{ trans('messages.manage_non_estates') }}</h1>

        @can('manage-non-estates')
        <!-- Button to View Non-Acquired Estates -->
        <a href="{{ route('estate.nonAcEstates.create') }}" class="btn btn-secondary">{{ trans('messages.create_non_acquired_estates') }}</a>
        @endcan
        
        <!-- Add other content as needed -->
        <a href="{{ route('estate.nonAcEstates') }}" class="btn btn-secondary">{{ trans('messages.view_non_acquired_estates') }}</a>

    </div>
</body>

</html>

