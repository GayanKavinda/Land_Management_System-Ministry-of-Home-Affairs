<!-- resources/views/nonEstate/searchResults.blade.php -->

@extends('layouts.app')

@section('content')

<div class="container">
    <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>

    <h1>Non Estate Search Results</h1>

    @if (isset($resultCount) && $resultCount > 0)

    <p>{{ $resultCount }} result(s) found for '{{ $searchQuery }}'</p>

    <a href="{{ route('downloadPdfNonEstates', ['searchQuery' => $searchQuery]) }}" class="btn btn-primary">Download PDF</a>

    <!-- Display search results in a table -->
    <table class="table">
        <thead>
            <tr>
                <th>Non Estate ID</th>
                <th>{{ trans('messages.province') }}</th>
                <th>{{ trans('messages.district') }}</th>
                <th>{{ trans('messages.divisional_secretariat') }}</th>
                <th>{{ trans('messages.grama_niladari_division') }}</th>
                <th>{{ trans('messages.estate_name') }}</th>
                <th>{{ trans('messages.plan_no') }}</th>
                <th>{{ trans('messages.land_extent') }}</th>
                <th>{{ trans('messages.building_available') }}</th>
                <th>{{ trans('messages.building_name') }}</th>
                <th>{{ trans('messages.government_land') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($nonEstates as $nonEstate)
            <tr>
                <td>{{ $nonEstate->id }}</td>
                <td>{{ $nonEstate->province }}</td>
                <td>{{ $nonEstate->district }}</td>
                <td>{{ $nonEstate->divisional_secretariat }}</td>
                <td>{{ $nonEstate->grama_niladari_division }}</td>
                <td>{{ $nonEstate->estate_name }}</td>
                <td>{{ $nonEstate->plan_no }}</td>
                <td>{{ $nonEstate->land_extent }}</td>
                <td>{{ $nonEstate->building_available ? 'Yes' : 'No' }}</td>
                <td>{{ $nonEstate->building_name }}</td>
                <td>{{ $nonEstate->government_land ? 'Yes' : 'No' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else

    <p>No results found for '{{ $searchQuery }}'</p>

    @endif

</div>

@endsection
