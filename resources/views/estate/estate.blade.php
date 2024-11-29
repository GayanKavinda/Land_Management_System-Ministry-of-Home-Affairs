<!DOCTYPE html>
<html lang="en">
<head>


@extends('layouts.app')

@section('content')


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Estate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">{{ __('messages.estate_details_form') }}</h3>
        </div>
        <div class="card-body">
            @if(session('status') && session('message'))
                <script>
                    var status = "{{ session('status') }}";
                    var message = "{{ session('message') }}";

                    if (status === 'success' || status === 'error') {
                        alert(message);
                    }
                </script>
            @endif

            <!-- Back button -->
            <a href="{{ url()->previous() }}" class="btn btn-secondary">{{ __('messages.back_button') }}</a>
            <a href="{{ route('showEstates') }}" class="btn btn-link float-end">{{ __('messages.view_acquired_estates') }}</a>

            <form method="POST" action="{{ route('addEstateData') }}" enctype="multipart/form-data" class="mt-3">
                @csrf

                <!-- Location Details -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="province" class="form-label">{{ __('messages.province') }}</label>
                        <input type="text" class="form-control" id="province" name="province" required>
                    </div>
                    <div class="col-md-4">
                        <label for="district" class="form-label">{{ __('messages.district') }}</label>
                        <input type="text" class="form-control" id="district" name="district" required>
                    </div>
                    <div class="col-md-4">
                        <label for="divisional_secretariat" class="form-label">{{ __('messages.divisional_secretariat') }}</label>
                        <input type="text" class="form-control" id="divisional_secretariat" name="divisional_secretariat" required>
                    </div>
                    <div class="col-md-4">
                        <label for="grama_niladari_division" class="form-label">{{ __('messages.grama_niladari_division') }}</label>
                        <input type="text" class="form-control" id="grama_niladari_division" name="grama_niladari_division" required>
                    </div>
                </div>

                <!-- Land Details -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="land_situated_village" class="form-label">{{ __('messages.land_situated_village') }}</label>
                        <input type="text" class="form-control" id="land_situated_village" name="land_situated_village" required>
                    </div>

                    <div class="col-md-4">
                        <label for="acquired_land_name" class="form-label">{{ __('messages.acquired_land_name') }}</label>
                        <input type="text" class="form-control" id="acquired_land_name" name="acquired_land_name" required>
                    </div>

                    <div class="col-md-4">
                        <label for="acquired_land_extent" class="form-label">{{ __('messages.acquired_land_extent') }}</label>
                        <input type="text" class="form-control" id="acquired_land_extent" name="acquired_land_extent" required>
                    </div>

                    <div class="col-md-4">
                        <label for="total_extent_allotment_included" class="form-label">{{ __('messages.total_extent_allotment_included') }}</label>
                        <input type="text" class="form-control" id="total_extent_allotment_included" name="total_extent_allotment_included" required>
                    </div>

                    <div class="col-md-4">
                        <label for="claimant_name_and_address" class="form-label">{{ __('messages.claimant_name_and_address') }}</label>
                        <input type="text" class="form-control" id="claimant_name_and_address" name="claimant_name_and_address" required>
                    </div>

                    <div class="col-md-4">
                        <label for="office_file_recorded" class="form-label">{{ __('messages.office_file_recorded') }}</label>
                        <input type="text" class="form-control" id="office_file_recorded" name="office_file_recorded" required>
                    </div>

                    <div class="col-md-4">
                        <label for="land_acquired_purpose" class="form-label">{{ __('messages.land_acquired_purpose') }}</label>
                        <input type="text" class="form-control" id="land_acquired_purpose" name="land_acquired_purpose" required>
                    </div>

                    <div class="col-md-4">
                        <label for="land_acquisition_certificate" class="form-label">{{ __('messages.land_acquisition_certificate') }}</label>
                        <input type="file" class="form-control" id="land_acquisition_certificate" name="land_acquisition_certificate" required>
                    </div>
                </div>

                <!-- Plan Availability -->
                <div class="mb-3">
                    <label for="plan_availability" class="form-label">{{ __('messages.plan_availability') }}</label>
                    <select class="form-select" id="plan_availability" name="plan_availability" onchange="togglePlanFields()" required>
                        <option value="yes">{{ __('messages.yes') }}</option>
                        <option value="no" selected>{{ __('messages.no') }}</option>
                    </select>
                </div>

                <!-- Plan and Image Fields -->
                <div class="row mb-3" id="planFields" style="display: none;">
                    <div class="col-md-6">
                        <label for="plan_no_and_lot_no" class="form-label">{{ __('messages.plan_no_and_lot_no') }}</label>
                        <input type="text" class="form-control" id="plan_no_and_lot_no" name="plan_no_and_lot_no">
                    </div>
                    <div class="col-md-6">
                        <label for="plan_image" class="form-label">{{ __('messages.plan_image') }}</label>
                        <input type="file" class="form-control" id="plan_image" name="plan_image">
                    </div>
                </div>
                
                <!-- Boundaries of Land -->
                <div class="mb-3">
                    <label for="boundaries_of_land" class="form-label">{{ __('messages.boundaries_of_land') }}</label>
                    <textarea class="form-control" id="boundaries_of_land" name="boundaries_of_land" rows="3" required></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">{{ __('messages.submit_button') }}</button>
            </form>
        </div>
    </div>
</div>


<script>
    function togglePlanFields() {
        var planNoAndLotNoField = document.getElementById("plan_no_and_lot_no");
        var planImageField = document.getElementById("plan_image");
        var boundariesOfLandField = document.getElementById("boundaries_of_land");
        var boundariesOfLandLabel = document.querySelector("label[for='boundaries_of_land']");
        var planAvailability = document.getElementById('plan_availability');

        if (planAvailability.value === 'yes') {
            planNoAndLotNoField.setAttribute("required", "required");
            planImageField.setAttribute("required", "required");
            boundariesOfLandField.value = 'No';
            boundariesOfLandField.style.display = 'none';
            boundariesOfLandLabel.style.display = 'none';
        } else {
            planNoAndLotNoField.removeAttribute("required");
            planImageField.removeAttribute("required");
            boundariesOfLandField.style.display = 'block';
            boundariesOfLandLabel.style.display = 'block';
        }

        var planFields = document.getElementById('planFields');

        if (planAvailability.value === 'yes') {
            planFields.style.display = 'flex';
        } else {
            planFields.style.display = 'none';
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-oGNNAv1qTLOL8Hl0PfwYDtdr7BzVRAwMG9C8rNfZq8w+G0lB4/Y4HA+7xkDDv8Ku" crossorigin="anonymous"></script>
</body>
</html>
@endsection