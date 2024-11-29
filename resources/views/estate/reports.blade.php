@extends('layouts.app')

@section('content')

<div class="container">

    <a href="{{ route('home') }}" class="btn btn-secondary">{{ trans('messages.back') }}</a>

    <!-- Your existing content goes here -->

    <h2 style="text-align: center;">{{ trans('messages.download_report') }}</h2>



    <div style="text-align: center;">
        <!-- Add a link or button to trigger the download -->
        <a href="{{ route('export.estate') }}" class="btn btn-primary">{{ trans('messages.export_estates_data') }}</a>
        <a href="{{ route('export.non.estate') }}" class="btn btn-primary">{{ trans('messages.export_non_estates_data') }}</a>
    </div>
</div>
@endsection
