<!-- resources/views/estates/create.blade.php -->

@extends('layouts.app')
<!-- Adjust as per your layout structure -->

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


@section('content')

<!-- Back button -->
<a href="{{ route('home') }}" class="btn btn-secondary">{{ __('messages.back_button') }}</a>

<div class="container mt-5">
    <h2>{{ __('messages.create_non_acquired_estate') }}</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ __('messages.success') }}</div>
    @endif

    <form action="{{ route('estate.nonAcEstates.store') }}" method="post" class="mt-3">
        @csrf

        <!-- New fields -->
        <div class="mb-3">
            <label for="province" class="form-label">{{ __('messages.province') }}:</label>
            <input type="text" name="province" id="province" value="{{ old('province') }}" class="form-control"
                required>
        </div>

        <div class="mb-3">
            <label for="district" class="form-label">{{ __('messages.district') }}:</label>
            <input type="text" name="district" id="district" value="{{ old('district') }}" class="form-control"
                required>
        </div>

        <div class="mb-3">
            <label for="divisional_secretariat" class="form-label">{{ __('messages.divisional_secretariat') }}:</label>
            <input type="text" name="divisional_secretariat" id="divisional_secretariat"
                value="{{ old('divisional_secretariat') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="grama_niladari_division"
                class="form-label">{{ __('messages.grama_niladari_division') }}:</label>
            <input type="text" name="grama_niladari_division" id="grama_niladari_division"
                value="{{ old('grama_niladari_division') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="estate_name" class="form-label">{{ __('messages.estate_name') }}:</label>
            <input type="text" name="estate_name" id="estate_name" value="{{ old('estate_name') }}" class="form-control"
                required>
        </div>

        <div class="mb-3">
            <label for="plan_no" class="form-label">{{ __('messages.plan_no') }}:</label>
            <input type="text" name="plan_no" id="plan_no" value="{{ old('plan_no') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="land_extent" class="form-label">{{ __('messages.land_extent') }}:</label>
            <input type="text" name="land_extent" id="land_extent" value="{{ old('land_extent') }}" class="form-control"
                required>
        </div>


        <div class="mb-3 form-check">
            <input type="checkbox" name="building_available" id="building_available" class="form-check-input" value="1"
                onchange="toggleBuildingNameField()">
            <label class="form-check-label" for="building_available">{{ __('messages.building_available') }}</label>
        </div>

        <div id="building_name_field" style="display: none;">
            <div class="mb-3">
                <label for="building_name" class="form-label">{{ __('messages.building_name') }}:</label>
                <input type="text" name="building_name" id="building_name" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label for="government_land" class="form-label">{{ __('messages.government_land') }}:</label>
            <select name="government_land" id="government_land" class="form-select" onchange="toggleReasonField()">
                <option value="yes">{{ __('messages.yes') }}</option>
                <option value="no">{{ __('messages.no') }}</option>
                <option value="cannot_specify">{{ __('messages.cannot_specify') }}</option>
            </select>
        </div>

        <div id="reason_field" style="display: none;">
            <div class="mb-3">
                <label for="reason" class="form-label">{{ __('messages.reason') }}:</label>
                <input type="text" name="reason" id="reason" class="form-control">
            </div>
        </div>

        <!-- Add more fields as needed -->

        <button type="submit" class="btn btn-primary">{{ __('messages.submit_button') }}</button>
    </form>
</div>


<script>
function toggleBuildingNameField() {
    const buildingNameField = document.getElementById('building_name_field');
    const buildingAvailableCheckbox = document.getElementById('building_available');

    buildingNameField.style.display = buildingAvailableCheckbox.checked ? 'block' : 'none';
}

function toggleReasonField() {
    const reasonField = document.getElementById('reason_field');
    const governmentLandSelect = document.getElementById('government_land');

    reasonField.style.display = governmentLandSelect.value === 'cannot_specify' ? 'block' : 'none';
}
</script>


@endsection