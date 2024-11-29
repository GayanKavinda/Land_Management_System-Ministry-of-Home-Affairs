<!DOCTYPE html>
<html lang="en">

<head>
    @extends('layouts.app')

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



    <link rel="stylesheet" href="{{ asset('css/filter.css') }}">

    @section('content')
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Estates</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.19.0/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/filter.css') }}">
    </head>

    <body>

    <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>

    
        <br />
        <div class="container">

        <!-- Add this at the top of your Blade file, before the table -->
        <form action="{{ route('search.estates') }}" method="GET">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search estates" name="search" value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
        </form>


            <!-- Your existing HTML form with added CSS classes -->
            <form action="{{ route('filterResults') }}" method="GET" class="form-container mb-4" onsubmit="return validateForm()">
                <div >
                    <!-- Province Dropdown -->
                    @if(isset($provinces))
                        <select name="province" id="province" class="form-select" required>
                            <option value="">{{ trans('messages.select_province') }}</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    @endif

                    <!-- District Dropdown (Initially Disabled) -->
                    <select name="district" id="district" class="form-select" disabled required>
                        <option value="">{{ trans('messages.select_district') }}</option>
                    </select>

                    <!-- Divisional Secretariat Dropdown (Initially Disabled) -->
                    <select name="divisional_secretariat" id="divisional_secretariat" class="form-select" disabled required>
                        <option value="">{{ trans('messages.select_divisional_secretariat') }}</option>
                    </select>

                    <!-- Grama Niladari Division Dropdown (Initially Disabled) -->
                    <select name="grama_niladari_division" id="grama_niladari_division" class="form-select" disabled required>
                        <option value="">{{ trans('messages.select_grama_niladari_division') }}</option>
                    </select>

                    <button type="submit" class="btn btn-info-filter">{{ trans('messages.filter_button') }}</button>
                </div>

                <!-- Error message for validation -->
                <div id="error-message" class="error-message"></div>
            </form>

            <div class="jumbotron">
                <h3 style="text-align: center;">{{ trans('messages.acquired_estate_details') }}</h3>
                <br />
                <table class="table table-stripped">
                    <thead>
                        <tr>
                        <th scope="col">Estate ID</th>
                        <th>{{ trans('messages.province') }}</th>
                        <th>{{ trans('messages.district') }}</th>
                        <th>{{ trans('messages.divisional_secretariat') }}</th>
                        <th>{{ trans('messages.grama_niladari_division') }}</th>
                        @can('manage-estates')
                        <th>{{ trans('messages.land_acquisition_certificate') }}</th>
                        <th>{{ trans('messages.plan_availability') }}</th>
                        <th>{{ trans('messages.plan_no_and_lot_no') }}</th>
                        <th>{{ trans('messages.plan_image') }}</th>
                        <th>{{ trans('messages.boundaries_of_land') }}</th>
                            
                        <th scope="col">{{ trans('messages.action') }}</th>
                        @endcan
                        @can('view-estates')
                            <th scope="col">{{ trans('messages.action') }}</th>
                        @endcan
                        </tr>

                        <tr>
                            <th colspan="11">
                                @if(isset($searchResults))
                                {{ count($searchResults) }} result(s) found for '{{ request('search') }}'
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estates as $estate)
                        <tr>
                            <th scope="row">{{ $estate->id }}</th>
                            <td>{{ $estate->province }}</td>
                            <td>{{ $estate->district }}</td>
                            <td>{{ $estate->divisional_secretariat }}</td>
                            <td>{{ $estate->grama_niladari_division }}</td>

                            @can('manage-estates')

                            <td>
                                <a href="{{ asset('uploads/images/' . $estate->land_acquisition_certificate) }}" download>
                                    
                                @if($estate->land_acquisition_certificate)
                                    <img src="{{ asset('uploads/images/' . $estate->land_acquisition_certificate) }}"
                                        alt="Land Acquisition Certificate" width="100" height="100">
                                    <i class="bi bi-download">Download</i> <!-- Bootstrap download icon -->
                                @else
                                    <p>Image Not Available</p>
                                @endif
                                </a>
                            </td>
                            <td>{{ $estate->plan_availability ? trans('messages.yes') : trans('messages.no') }}</td>
                            <td>{{ $estate->plan_no_and_lot_no }}</td>
                            <td>
                            <a href="{{ asset('uploads/images/' . $estate->plan_image) }}" download>
                                @if($estate->plan_image)
                                    <img src="{{ asset('uploads/images/' . $estate->plan_image) }}" alt="Plan Image" width="100" height="100">
                                    <i class="bi bi-download">Download</i> <!-- Bootstrap download icon -->
                                @else
                                    <p>Image Not Available</p>
                                @endif
                            </a>

                            </td>

                            <td>{{ $estate->boundaries_of_land ? : trans('messages.no')}}</td>
                            
                            <td>
                                <a href="{{ route('showData', ['id' => $estate->id]) }}"
                                    class="btn btn-primary">{{ trans('messages.show') }}</a>
                                <form id="deleteForm{{ $estate->id }}" action="{{ route('deleteEstate', ['id' => $estate->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $estate->id }})" class="btn btn-danger">{{ trans('messages.delete_button') }}</button>
                                </form>
                            </td>
                            @endcan

                            @can('view-estates')
                            <td>
                                <a href="{{ route('showData', ['id' => $estate->id]) }}" class="btn btn-dark">View Full</a>
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                        {{ $estates->onEachSide(1)->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>


            </div>
        </div>

        <!-- JavaScript function for delete confirmation -->
        <script>
            function confirmDelete(estateId) {
                if (confirm("Are you sure you want to delete this estate?")) {
                    document.getElementById('deleteForm' + estateId).submit();
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
                        $.get('/get-districts-by-province', { province: selectedProvince }, function (data) {
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
                        $.get('/get-divisional-secretariats-by-district', { district: selectedDistrict }, function (data) {
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
                        $.get('/get-grama-niladari-divisions-by-divisional-secretariat', { divisional_secretariat: selectedDivisionalSecretariat }, function (data) {
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
        </script>



    </body>

    </html>
    @endsection
