<!-- resources/views/nonEstates/edit.blade.php -->

@extends('layouts.app') <!-- Adjust as per your layout structure -->

@section('content')

<a href="{{ route('home') }}" class="btn btn-secondary">Back</a>


    <div class="container mt-5">
        <h2>Edit Non-Acquired Estate</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('estate.nonAcEstates.update', $nonEstate->id) }}" method="post" class="mt-3">
            @csrf
            @method('put') <!-- Use PUT method for update -->

            <!-- Existing fields -->
            <input type="hidden" name="id" value="{{ $nonEstate->id }}">
            
            <div class="mb-3">
                <label for="province" class="form-label">Province:</label>
                <input type="text" name="province" id="province" value="{{ old('province', $nonEstate->province) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="district" class="form-label">District:</label>
                <input type="text" name="district" id="district" value="{{ old('district', $nonEstate->district) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <!-- Add more fields as needed -->
                <!-- Example: -->
                <label for="divisional_secretariat" class="form-label">Divisional Secretariat:</label>
                <input type="text" name="divisional_secretariat" id="divisional_secretariat" value="{{ old('divisional_secretariat', $nonEstate->divisional_secretariat) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="grama_niladari_division" class="form-label">Grama Niladari Division:</label>
                <input type="text" name="grama_niladari_division" id="grama_niladari_division" value="{{ old('grama_niladari_division', $nonEstate->grama_niladari_division) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="estate_name" class="form-label">Estate Name:</label>
                <input type="text" name="estate_name" id="estate_name" value="{{ old('estate_name', $nonEstate->estate_name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="plan_no" class="form-label">Plan No:</label>
                <input type="text" name="plan_no" id="plan_no" value="{{ old('plan_no', $nonEstate->plan_no) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="land_extent" class="form-label">Land Extent:</label>
                <input type="text" name="land_extent" id="land_extent" value="{{ old('land_extent', $nonEstate->land_extent) }}" class="form-control" required>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="building_available" id="building_available" class="form-check-input" {{ $nonEstate->building_available ? 'checked' : '' }} onchange="toggleBuildingNameField()">
                <label class="form-check-label" for="building_available">Building Available</label>
            </div>

            <div id="building_name_field" style="display: {{ $nonEstate->building_available ? 'block' : 'none' }};">
                <div class="mb-3">
                    <label for="building_name" class="form-label">Building Name:</label>
                    <input type="text" name="building_name" id="building_name" value="{{ old('building_name', $nonEstate->building_name) }}" class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label for="government_land" class="form-label">{{ __('messages.government_land') }}:</label>
                <select name="government_land" id="government_land" class="form-select" onchange="toggleReasonField()">
                    <option value="yes" {{ $nonEstate->government_land === 'yes' ? 'selected' : '' }}>{{ __('messages.yes') }}</option>
                    <option value="no" {{ $nonEstate->government_land === 'no' ? 'selected' : '' }}>{{ __('messages.no') }}</option>
                    <option value="cannot_specify" {{ $nonEstate->government_land === 'cannot_specify' ? 'selected' : '' }}>{{ __('messages.cannot_specify') }}</option>
                </select>
            </div>

            <div id="reason_field" style="display: {{ $nonEstate->government_land === 'cannot_specify' ? 'block' : 'none' }};">
                <div class="mb-3">
                    <label for="reason" class="form-label">{{ __('messages.reason') }}:</label>
                    <input type="text" name="reason" id="reason" value="{{ old('reason', $nonEstate->reason) }}" class="form-control">
                </div>
            </div>



        @can('manage-non-estates')
            <button type="submit" class="btn btn-primary">Update</button>
        @endcan
        </form>
    </div>
    <div>
        
    <script>
        // Wait for the DOM content to be fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            // Get references to the elements
            const buildingNameField = document.getElementById('building_name_field');
            const buildingAvailableCheckbox = document.getElementById('building_available');

            // Set the initial display state based on the checkbox
            buildingNameField.style.display = buildingAvailableCheckbox.checked ? 'block' : 'none';

            // Add an event listener to the checkbox
            buildingAvailableCheckbox.addEventListener('change', function () {
                // Update the display state when the checkbox state changes
                buildingNameField.style.display = this.checked ? 'block' : 'none';
            });
        });



        function toggleBuildingNameField() {
        const buildingNameField = document.getElementById('building_name_field');
        const buildingAvailableCheckbox = document.getElementById('building_available');
        const buildingNameInput = document.getElementById('building_name');

        if (buildingAvailableCheckbox.checked) {
            buildingNameField.style.display = 'block';
        } else {
            buildingNameField.style.display = 'none';
            // Clear the value when building available is unchecked
            buildingNameInput.value = '';
        }
    }

    function toggleReasonField() {
        const reasonField = document.getElementById('reason_field');
        const governmentLandSelect = document.getElementById('government_land');

        reasonField.style.display = governmentLandSelect.value === 'cannot_specify' ? 'block' : 'none';
    }



    </script>



@endsection
