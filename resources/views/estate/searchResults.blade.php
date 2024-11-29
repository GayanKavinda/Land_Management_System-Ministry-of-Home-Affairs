@extends('layouts.app')

@section('content')

<div class="container">
    <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
    <h1>Search Results</h1>

    @if (isset($estates) && $estates->isNotEmpty())
    <p>{{ $estates->count() }} result(s) found for '{{ $searchQuery }}'</p>
    <form action="{{ route('downloadAndProvidePdf', ['searchQuery' => $searchQuery]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Download PDF</button>
    </form>

    <!-- Display search results in a table -->
    <table class="table">
        <thead>
            <tr>
                <th>Estate ID</th>
                <th>{{ trans('messages.province') }}</th>
                <th>{{ trans('messages.district') }}</th>
                <th>{{ trans('messages.divisional_secretariat') }}</th>
                <th>{{ trans('messages.grama_niladari_division') }}</th>
                <th>{{ trans('messages.land_acquisition_certificate') }}</th>
                <th>{{ trans('messages.plan_availability') }}</th>
                <th>{{ trans('messages.plan_no_and_lot_no') }}</th>
                <th>{{ trans('messages.plan_image') }}</th>
                <th>{{ trans('messages.boundaries_of_land') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($estates as $estate)
            <tr>
                <td>{{ $estate->id }}</td>
                <td>{{ $estate->province }}</td>
                <td>{{ $estate->district }}</td>
                <td>{{ $estate->divisional_secretariat }}</td>
                <td>{{ $estate->grama_niladari_division }}</td>
                <td>
                    @if ($estate->land_acquisition_certificate)
                        <a href="{{ asset('uploads/images/' . $estate->land_acquisition_certificate) }}" target="_blank">Preview</a>
                        <br>
                        <img src="{{ asset('uploads/images/' . $estate->land_acquisition_certificate) }}" alt="Land Acquisition Certificate" style="max-width: 100px;">
                    @else
                        No Image Available
                    @endif
                </td>
                <td>{{ $estate->plan_availability }}</td>
                <td>{{ $estate->plan_no_and_lot_no }}</td>
                <td>
                    @if ($estate->plan_image)
                        <a href="{{ asset('uploads/images/' . $estate->plan_image) }}" target="_blank">Preview</a>
                        <br>
                        <img src="{{ asset('uploads/images/' . $estate->plan_image) }}" alt="Plan Image" style="max-width: 100px;">
                    @else
                        No Image Available
                    @endif
                </td>
                <td>{{ $estate->boundaries_of_land }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <p>No results found for '{{ $searchQuery }}'</p>
    @endif
</div>

@endsection
