<!-- resources/views/estate/nonAcEstates/view.blade.php -->

@extends('layouts.app') <!-- Adjust as per your layout structure -->

<link rel="stylesheet" href="{{ asset('css/filter.css') }}">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Popper.js, required for Bootstrap's JavaScript plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



@section('content')

    <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>

    <div class="container">

    <!-- Add this at the top of your Blade file, before the table -->
    <form action="{{ route('search.nonEstates') }}" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search estates" name="search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>

        

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <div>
            <!-- Filter Form -->
            <form method="GET" action="{{ route('estate.nonAcEstates') }}" class="form-container mb-4">
                @csrf
                <div >  
                            <select name="province" id="province" class="form-select">
                                <option value="">{{ trans('messages.select_province') }}</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}" {{ request('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                @endforeach
                            </select>
               

                            <select name="district" id="district" class="form-select" disabled >
                                <option value="">{{ trans('messages.select_district') }}</option>
                            </select>
                 

                            <select name="divisional_secretariat" id="divisional_secretariat" class="form-select" disabled>
                                <option value="">{{ trans('messages.select_divisional_secretariat') }}</option>
                            </select>
  

                            <select name="grama_niladari_division" id="grama_niladari_division" class="form-select" disabled>
                                <option value="">{{ trans('messages.select_grama_niladari_division') }}</option>
                            </select>

                        <button type="submit" class="btn-info-filter" onclick="return validateForm()">{{ trans('messages.filter_button') }}</button>
                   
                </div>

                <!-- Error message for validation -->
                <div id="error-message" class="error-message"></div>
            </form>
        </div>

        <h2 style="text-align: center;">{{ trans('messages.non_acquired_estate_details') }}</h2>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
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
                    <th>{{ trans('messages.reason') }}</th>
                    <!-- Add more columns as needed -->

                    <!-- Actions Column -->
                    <th>{{ trans('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nonEstates as $nonEstate)
                    <tr>
                        <td>{{ $nonEstate->id }}</td>
                        <td>{{ $nonEstate->province }}</td>
                        <td>{{ $nonEstate->district }}</td>
                        <td>{{ $nonEstate->divisional_secretariat }}</td>
                        <td>{{ $nonEstate->grama_niladari_division }}</td>
                        <td>{{ $nonEstate->estate_name }}</td>
                        <td>{{ $nonEstate->plan_no }}</td>
                        <td>{{ $nonEstate->land_extent }}</td>
                        <td>{{ $nonEstate->building_available ? trans('messages.yes') : trans('messages.no') }}</td>
                        <td>{{ $nonEstate->building_name }}</td>
                        <td>{{ $nonEstate->government_land === 'yes' ? __('messages.yes') : ($nonEstate->government_land === 'no' ? __('messages.no') : __('messages.cannot_specify')) }}</td>
                        <td>{{ $nonEstate->reason }}</td>
                        <!-- Add more columns as needed -->

                        <!-- Actions Column -->
                        @can('manage-non-estates')

                        <td>
                            <!-- Add edit, delete, and move actions -->
                            <a href="{{ route('estate.nonAcEstates.edit', $nonEstate->id) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('estate.nonAcEstates.destroy', $nonEstate->id) }}" method="post" id="deleteForm{{ $nonEstate->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete({{ $nonEstate->id }})" class="btn btn-danger">{{ trans('messages.delete_button') }}</button>
                            </form>

                            <!-- Move to Acquired Estate button -->
                            <button type="button" class="btn btn-success" onclick="openMoveToAcquiredModal({{ $nonEstate->id }})">Move to Acquired Estate</button>
                        </td>

                        <!-- Move to Acquired Estate Modal -->
                        <div class="modal fade" id="moveToAcquiredModal" tabindex="-1" role="dialog" aria-labelledby="moveToAcquiredModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="moveToAcquiredModalLabel">Move to Acquired Estate</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeMoveToAcquiredModal()" >
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Move to Acquired Estate form with additional information -->
                                        <form id="moveToAcquiredForm" action="{{ route('estate.moveToAcquired', ['id' => ':id']) }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST') 

                                            <!-- Additional information fields -->
                                            
                                            <div class="form-group">
                                                <label for="land_situated_village">Land Situated Village</label>
                                                <input type="text" name="land_situated_village" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="acquired_land_name">Acquired Land Name</label>
                                                <input type="text" name="acquired_land_name" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="acquired_land_extent">Acquired Land Extent</label>
                                                <input type="text" name="acquired_land_extent" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="total_extent_allotment_included">Total Extent Allotment Included</label>
                                                <input type="text" name="total_extent_allotment_included" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="claimant_name_and_address">Claimant Name and Address</label>
                                                <input type="text" name="claimant_name_and_address" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="office_file_recorded">Office File Recorded</label>
                                                <input type="text" name="office_file_recorded" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="land_acquired_purpose">Land Acquired Purpose</label>
                                                <input type="text" name="land_acquired_purpose" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="land_acquisition_certificate">Land Acquisition Certificate</label>
                                                <input type="file" name="land_acquisition_certificate" class="form-control" accept="image/*" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="plan_image">Plan Image</label>
                                                <input type="file" name="plan_image" class="form-control" accept="image/*" required>
                                            </div>

                                            <div class="form-group" id="planAvailabilityGroup">
                                                <label for="plan_availability">Plan Availability</label>
                                                <select id="plan_availability" name="plan_availability" class="form-control" required>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>

                                            <div class="form-group" id="planNoAndLotNoGroup">
                                                <label for="plan_no_and_lot_no">Plan No and Lot No</label>
                                                <input type="text" name="plan_no_and_lot_no" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="boundaries_of_land">Boundaries of Land</label>
                                                <input type="text" name="boundaries_of_land" class="form-control">
                                            </div>

                                            <!-- Submit button -->
                                            <button type="button" class="btn btn-success" onclick="submitMoveToAcquiredForm()">Submit</button>

                                             <!-- Close button -->
                                            <button type="button" class="btn btn-secondary" onclick="closeMoveToAcquiredModal()">Close</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endcan

                        @can('view-non-estates')
                            <td>
                                <a href="{{ route('estate.nonAcEstates.edit', ['id' => $nonEstate->id]) }}" class="btn btn-dark">View Full</a>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    {{ $nonEstates->onEachSide(1)->links('pagination::bootstrap-4') }}
                </ul>
            </nav>
        </div>


    </div>

    <script>
        // JavaScript function to open the modal
        function openMoveToAcquiredModal(nonEstateId) {
                var formAction = "{{ route('estate.moveToAcquired', ['id' => ':id']) }}".replace(':id', nonEstateId);
                $('#moveToAcquiredForm').attr('action', formAction);
                $('#moveToAcquiredModal').modal('show');
            }

            function submitMoveToAcquiredForm() {
                // Perform client-side validation before submitting the form
                var landSituatedVillage = document.forms["moveToAcquiredForm"]["land_situated_village"].value;
                var acquiredLandName = document.forms["moveToAcquiredForm"]["acquired_land_name"].value;
                var acquiredLandExtent = document.forms["moveToAcquiredForm"]["acquired_land_extent"].value;
                var totalExtentAllotmentIncluded = document.forms["moveToAcquiredForm"]["total_extent_allotment_included"].value;
                var claimantNameAndAddress = document.forms["moveToAcquiredForm"]["claimant_name_and_address"].value;
                var officeFileRecorded = document.forms["moveToAcquiredForm"]["office_file_recorded"].value;
                var landAcquiredPurpose = document.forms["moveToAcquiredForm"]["land_acquired_purpose"].value;
                var landAcquisitionCertificate = document.forms["moveToAcquiredForm"]["land_acquisition_certificate"].value;
                var planImage = document.forms["moveToAcquiredForm"]["plan_image"].value;
                var planAvailability = document.forms["moveToAcquiredForm"]["plan_availability"].value;
                var boundariesOfLand = document.forms["moveToAcquiredForm"]["boundaries_of_land"].value;
                var planNoAndLotNo = document.forms["moveToAcquiredForm"]["plan_no_and_lot_no"].value;

                // Check if required fields are filled in
                if (landSituatedVillage === "" || acquiredLandName === "" || acquiredLandExtent === "" ||
                    totalExtentAllotmentIncluded === "" || claimantNameAndAddress === "" || officeFileRecorded === "" ||
                    landAcquiredPurpose === "" || boundariesOfLand === "") {
                    alert("Please fill in all required fields.");
                    return false;
                }

                // Check if file fields have a selected file
                if (landAcquisitionCertificate === "") {
                    alert("Please select a Land Acquisition Certificate file.");
                    return false;
                }

                if (planImage === "") {
                    alert("Please select a Plan Image file.");
                    return false;
                }

                // Check if "Plan Availability" is set to "Yes"
                if (planAvailability === "1") {
                    // If "Yes", check if "Plan No and Lot No" is filled in
                    if (planNoAndLotNo === "") {
                        alert("Please fill in Plan No and Lot No since Plan Availability is set to Yes.");
                        return false;
                    }
                }

                // If all validations pass, submit the form
                document.getElementById("moveToAcquiredForm").submit();
            }


        function confirmDelete(nonEstateId) {
            if (confirm("Are you sure you want to delete this estate?")) {
                document.getElementById('deleteForm' + nonEstateId).submit();
            }
        }

        $(document).ready(function () {
            $('#province').change(function () {
                var selectedProvince = $(this).val();
                if (selectedProvince) {
                    // Enable district dropdown
                    $('#district').prop('disabled', false);
                    // Clear and disable other dropdowns
                    $('#divisional_secretariat, #grama_niladari_division').empty().prop('disabled', true);
                    // Fetch districts based on the selected province
                    $.get('/get-districts-by-province-nonestate', { province: selectedProvince }, function (data) {
                        console.log(data);  // Log the data received from the server
                        updateDropdown('#district', data);
                    });
                } else {
                    // If no province is selected, disable all dropdowns
                    $('#district, #divisional_secretariat, #grama_niladari_division').empty().prop('disabled', true);
                }
            });

            $('#district').change(function () {
                var selectedDistrict = $(this).val();
                if (selectedDistrict) {
                    // Enable divisional secretariat dropdown
                    $('#divisional_secretariat').prop('disabled', false);
                    // Clear and disable other dropdowns
                    $('#grama_niladari_division').empty().prop('disabled', true);
                    // Fetch divisional secretariats based on the selected district
                    $.get('/get-divisional-secretariats-by-district-nonestate', { district: selectedDistrict }, function (data) {
                        updateDropdown('#divisional_secretariat', data);
                    });
                } else {
                    // If no district is selected, disable relevant dropdowns
                    $('#divisional_secretariat, #grama_niladari_division').empty().prop('disabled', true);
                }
            });

            $('#divisional_secretariat').change(function () {
                var selectedDivisionalSecretariat = $(this).val();
                if (selectedDivisionalSecretariat) {
                    // Enable grama niladari division dropdown
                    $('#grama_niladari_division').prop('disabled', false);
                    // Fetch grama niladari divisions based on the selected divisional secretariat
                    $.get('/get-grama-niladari-divisions-by-divisional-secretariat-nonestate', { divisional_secretariat: selectedDivisionalSecretariat }, function (data) {
                        updateDropdown('#grama_niladari_division', data);
                    });
                } else {
                    // If no divisional secretariat is selected, disable relevant dropdown
                    $('#grama_niladari_division').empty().prop('disabled', true);
                }
            });

            function updateDropdown(dropdownId, options) {
                var dropdown = $(dropdownId);
                dropdown.empty();
                dropdown.append('<option value="">Select ' + dropdownId.charAt(1).toUpperCase() + dropdownId.slice(2).replace('_', ' ') + '</option>');
                $.each(options, function (index, option) {
                    dropdown.append('<option value="' + option + '">' + option + '</option>');
                });
            }
        });

        function validateForm() {
            // Basic validation for province
            var province = document.getElementById('province').value;
            if (!province) {
                showError('Please select a province.');
                return false;
            }

            // Basic validation for district
            var district = document.getElementById('district').value;
            if (!district) {
                showError('Please select a district.');
                return false;
            }

            // Basic validation for divisional secretariat
            var divisionalSecretariat = document.getElementById('divisional_secretariat').value;
            if (!divisionalSecretariat) {
                showError('Please select a divisional secretariat.');
                return false;
            }

            // Basic validation for grama niladari division
            var gramaNiladariDivision = document.getElementById('grama_niladari_division').value;
            if (!gramaNiladariDivision) {
                showError('Please select a grama niladari division.');
                return false;
            }

            // Clear any previous error messages
            clearError();
            return true;
        }

        function showError(message) {
            document.getElementById('error-message').innerHTML = message;
        }

        function clearError() {
            document.getElementById('error-message').innerHTML = '';
        }


        function closeMoveToAcquiredModal() {
            $('#moveToAcquiredModal').modal('hide');
        }




        function handlePlanAvailabilityChange() {
            var planAvailability = $('#plan_availability').val(); // Assuming the dropdown ID is "plan_availability"
            var planNoAndLotNoGroup = $('#planNoAndLotNoGroup');

            if (planAvailability === '0') { // If "No" is selected
                planNoAndLotNoGroup.hide();
            } else {
                planNoAndLotNoGroup.show();
            }
        }

        // Attach the function to the change event of the "Plan Availability" dropdown
        $('#plan_availability').change(handlePlanAvailabilityChange);

        // Trigger the function on page load to handle initial state
        $(document).ready(function() {
            handlePlanAvailabilityChange();
        });

        
    </script>
@endsection
