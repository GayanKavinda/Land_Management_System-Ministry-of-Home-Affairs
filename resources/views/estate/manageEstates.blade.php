<!-- manageEstates.blade.php -->

<!DOCTYPE html>
<html lang="en">

<body>
    <div class="container">
        <h1>{{trans('messages.manage_estates')}}</h1>

        <!-- Button to View Acquired Estates -->
        <a href="{{ route('showEstates') }}" class="btn btn-secondary">{{trans('messages.view_acquired_estates')}}</a>

        @can('manage-estates')
        <!-- Button to Create Estate -->
        <a href="{{ route('estate') }}" class="btn btn-secondary">{{trans('messages.create_estate')}}</a>
        @endcan
		
		@can('show-estates')
		
			<h1> Hello, This is Show Estates </h1>
		@endcan
		
		
    </div>

    <br>

    <div>
        <h1>{{ trans('messages.manage_non_estates') }}</h1>
        <!-- Add other content as needed -->
        <a href="{{ route('estate.nonAcEstates') }}" class="btn btn-secondary">{{ trans('messages.view_non_acquired_estates') }}</a>

        @can('manage-non-estates')
        <!-- Button to View Non-Acquired Estates -->
        <a href="{{ route('estate.nonAcEstates.create') }}" class="btn btn-secondary">{{ trans('messages.create_non_acquired_estates') }}</a>
        @endcan
        
    </div>


</body>

</html>