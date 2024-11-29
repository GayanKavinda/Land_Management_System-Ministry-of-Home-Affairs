<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Estate Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .jumbotron {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1.display-4 {
            color: #007bff;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .btn-back {
            margin-bottom: 20px;
        }

        .btn-update {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    @can('manage-estates')
        @include('navbar')
    @endcan
 
<div class="container">
    <div class="jumbotron">
    @can('manage-estates')
        <h1 class="display-4 text-center">Update Estate Details</h1>
    @endcan       
        <!-- Back button -->
        <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>

        <form method="POST" action="{{ route('updateEstate', ['id' => $estate->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h2>Basic Information</h2>
                        <ul class="list-group">
							<li class="list-group-item"><strong> Estate ID: </strong> {{ $estate->id }}</li>
							<li class="list-group-item highlight-row"><strong>Province:</strong> <input type="text" name="province" value="{{ $estate->province }}"></li>
							<li class="list-group-item "><strong>District: </strong><input type="text" name="district" value="{{ $estate->district }}"></li>
							<li class="list-group-item highlight-row"><strong>Divisional Secretariat: </strong><input type="text" name="divisional_secretariat" value="{{ $estate->divisional_secretariat }}"></li>
							<li class="list-group-item "><strong>Grama Niladari Division:</strong> <input type="text" name="grama_niladari_division" value="{{ $estate->grama_niladari_division }}"></li>
							<li class="list-group-item highlight-row"><strong>Land Situated Village:</strong> <input type="text" name="land_situated_village" value="{{ $estate->land_situated_village }}"></li>
							<li class="list-group-item "><strong>Acquired Land Name:</strong> <input type="text" name="acquired_land_name" value="{{ $estate->acquired_land_name }}"></li>
							<li class="list-group-item highlight-row"><strong>Acquired Land Extent:</strong> <input type="text" name="acquired_land_extent" value="{{ $estate->acquired_land_extent }}"></li>
							<li class="list-group-item "><strong>Total Extent Allotment Included:</strong> <input type="text" name="total_extent_allotment_included" value="{{ $estate->total_extent_allotment_included }}"></li>
							<li class="list-group-item highlight-row"><strong>Claimant Name and Address:</strong> <input type="text" name="claimant_name_and_address" value="{{ $estate->claimant_name_and_address }}"></li>
							<li class="list-group-item "><strong>Office File Recorded: </strong> <input type="text" name="office_file_recorded" value="{{ $estate->office_file_recorded }}"></li>
							<li class="list-group-item highlight-row"><strong>Land Acquired Purpose:</strong> <input type="text" name="land_acquired_purpose" value="{{ $estate->land_acquired_purpose }}"></li>
							<li class="list-group-item "><strong>Land Acquisition Certificate:</strong> <input type="file" name="land_acquisition_certificate"></li>
							<li class="list-group-item highlight-row"><strong>Plan Availability: </strong>
								<select name="plan_availability">
									<option value="yes" {{ $estate->plan_availability === 'yes' ? 'selected' : '' }}>{{ trans('messages.yes') }}</option>
									<option value="no" {{ $estate->plan_availability === 'no' ? 'selected' : '' }}>{{ trans('messages.no') }}</option>
								</select>
							</li>
							<li class="list-group-item "><strong>Plan No and Lot No:</strong> <input type="text" name="plan_no_and_lot_no" value="{{ $estate->plan_no_and_lot_no }}"></li>
							<li class="list-group-item highlight-row">
                                <strong>Boundaries of Land:</strong>
                                <textarea name="boundaries_of_land" class="form-control">{{ $estate->boundaries_of_land }}</textarea>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <h2>Images</h2>
                        <label for="land_acquisition_certificate">Land Acquisition Certificate:</label>
						<img src="{{ asset('uploads/images/' . $estate->land_acquisition_certificate) }}" alt="Land Acquisition Certificate" class="img-fluid highlight-row" width="300" height="300">
						<input type="file" name="land_acquisition_certificate">
						<br><br>
						<label for="plan_image">Plan Image:</label>
						<img src="{{ asset('uploads/images/' . $estate->plan_image) }}" alt="Plan Image" class="img-fluid highlight-row" width="300" height="300">
						<input type="file" name="plan_image">
                    </div>
                </div>
            </div>
            @can('manage-estates')
            <button type="submit" class="btn btn-primary btn-update">Update</button>
            @endcan
        </form>

        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-oGNNAv1qTLOL8Hl0PfwYDtdr7BzVRAwMG9C8rNfZq8w+G0lB4/Y4HA+7xkDDv8Ku" crossorigin="anonymous"></script>
</body>
</html>
