<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estate;
use App\Models\NonEstate;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use League\Csv\Writer;
use Dompdf\Dompdf;
use Dompdf\Options;


class EstateController extends Controller
{
    public function index(){
        return view('estate.estate');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');

        // Using Eloquent's where method with a closure for more flexibility
        $estates = Estate::where(function ($query) use ($searchQuery) {
            $query->where('province', 'LIKE', "%$searchQuery%")
                ->orWhere('district', 'LIKE', "%$searchQuery%")
                ->orWhere('divisional_secretariat', 'LIKE', "%$searchQuery%")
                ->orWhere('grama_niladari_division', 'LIKE', "%$searchQuery%");
            // Add more conditions as needed
        })->get();

        // Pass the count directly to the view
        return view('estate.searchResults', compact('estates', 'searchQuery'))->with('resultCount', $estates->count());
    }

    public function downloadAndProvidePdf($searchQuery)
    {
        // Generate PDF content
        $pdfContent = $this->generatePdfContent($searchQuery);

        // Create a new Dompdf instance
        $dompdf = new Dompdf();
        
        // Load font
        $fontPath = public_path('fonts/IskoolaPotaRegular.ttf');
        $dompdf->getOptions()->setFontDir(public_path('fonts/'));
        $dompdf->getOptions()->setDefaultFont('IskoolaPotaRegular');
        $dompdf->getOptions()->setFontHeightRatio(1.1);
        
        // Load HTML content for PDF
        $dompdf->loadHtml($pdfContent);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper('A3', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output PDF to file
        $pdfFilePath = storage_path('app/pdf/search_results_' . now()->format('YmdHis') . '.pdf');
        file_put_contents($pdfFilePath, $dompdf->output());

        // Download the generated PDF
        return response()->download($pdfFilePath)->deleteFileAfterSend(true);
    }

    private function generatePdfContent($searchQuery)
    {
        // Retrieve search results
        $estates = Estate::where(function ($query) use ($searchQuery) {
            $query->where('province', 'LIKE', "%$searchQuery%")
                ->orWhere('district', 'LIKE', "%$searchQuery%")
                ->orWhere('divisional_secretariat', 'LIKE', "%$searchQuery%")
                ->orWhere('grama_niladari_division', 'LIKE', "%$searchQuery%");
        })->get();

        // Generate HTML table content for search results
        $html = '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Estate ID</th>';
        $html .= '<th>Province</th>';
        $html .= '<th>District</th>';
        $html .= '<th>Divisional Secretariat</th>';
        $html .= '<th>Grama Niladari Division</th>';
        $html .= '<th>Land Acquisition Certificate</th>';
        $html .= '<th>Plan Availability</th>';
        $html .= '<th>Plan No and Lot No</th>';
        $html .= '<th>Plan Image</th>';
        $html .= '<th>Boundaries of Land</th>';
        // Add more table headers as needed
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        foreach ($estates as $estate) {
            $html .= '<tr>';
            $html .= '<td>' . $estate->id . '</td>';
            $html .= '<td>' . $estate->province . '</td>';
            $html .= '<td>' . $estate->district . '</td>';
            $html .= '<td>' . $estate->divisional_secretariat . '</td>';
            $html .= '<td>' . $estate->grama_niladari_division . '</td>';
            $html .= '<td>';
            if ($estate->land_acquisition_certificate) {
                $html .= '<a href="' . asset('uploads/images/' . $estate->land_acquisition_certificate) . '" target="_blank">Preview</a><br>';
                $html .= '<img src="' . asset('uploads/images/' . $estate->land_acquisition_certificate) . '" alt="Land Acquisition Certificate" style="max-width: 100px;">';
            } else {
                $html .= 'No Image Available';
            }
            $html .= '</td>';
            $html .= '<td>' . $estate->plan_availability . '</td>';
            $html .= '<td>' . $estate->plan_no_and_lot_no . '</td>';
            $html .= '<td>';
            if ($estate->plan_image) {
                $html .= '<a href="' . asset('uploads/images/' . $estate->plan_image) . '" target="_blank">Preview</a><br>';
                $html .= '<img src="' . asset('uploads/images/' . $estate->plan_image) . '" alt="Plan Image" style="max-width: 100px;">';
            } else {
                $html .= 'No Image Available';
            }
            $html .= '</td>';
            $html .= '<td>' . $estate->boundaries_of_land . '</td>';
            // Add more table data as needed
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }


    public function moveToAcquired(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'land_situated_village' => 'required|string',
            'claimant_name_and_address' => 'required|string',
            'office_file_recorded' => 'required|string',
            'land_acquired_purpose' => 'required|string',
            'acquired_land_name' => 'required|string',
            'acquired_land_extent' => 'required|string',
            'total_extent_allotment_included' => 'required|string',
            'plan_no_and_lot_no' => 'nullable|string',
            'boundaries_of_land' => 'required|string',
            'land_acquisition_certificate' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'plan_availability' => 'required|boolean',
            'plan_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        // Retrieve the Non Acquired Estate by ID
        $nonEstate = NonEstate::findOrFail($id);

        // Handle file upload for land_acquisition_certificate
        if ($request->hasFile('land_acquisition_certificate')) {
            $certificateImage = $request->file('land_acquisition_certificate');
            $certificateImageName = 'certificate_' . time() . '.' . $certificateImage->getClientOriginalExtension();
            $certificateImage->move('uploads/images/', $certificateImageName);
        } else {
            return redirect()->back()->withErrors(['error' => 'Land Acquisition Certificate file is required.']);
        }

        // Handle file upload for plan_image
        if ($request->hasFile('plan_image')) {
            $planImage = $request->file('plan_image');
            $planImageName = 'plan_' . time() . '.' . $planImage->getClientOriginalExtension();
            $planImage->move('uploads/images/', $planImageName);
        } else {
            return redirect()->back()->withErrors(['error' => 'Plan Image file is required.']);
        }




        // Create an AcquiredEstate and save the additional information
        $acquiredEstate = new Estate([
            'province' => $nonEstate->province,
            'district' => $nonEstate->district,
            'divisional_secretariat' => $nonEstate->divisional_secretariat,
            'grama_niladari_division' => $nonEstate->grama_niladari_division,
            'land_situated_village' => $request->input('land_situated_village') ?? 'Not Available',
            'acquired_land_name' => $request->input('acquired_land_name') ?? 'Not Available',
            'acquired_land_extent' => $request->input('acquired_land_extent') ?? 'Not Available',
            'total_extent_allotment_included' => $request->input('total_extent_allotment_included') ?? 'Not Available',
            'claimant_name_and_address' => $request->input('claimant_name_and_address') ?? 'Not Available',
            'office_file_recorded' => $request->input('office_file_recorded') ?? 'Not Available',
            'land_acquired_purpose' => $request->input('land_acquired_purpose') ?? 'Not Available',
            'land_acquisition_certificate' => $certificateImageName ?? 'Not Available',
            'plan_availability' => $request->input('plan_availability') ?? 'Not Available',
            'plan_no_and_lot_no' => $request->input('plan_no_and_lot_no') ?? 'Not Available',
            'boundaries_of_land' => $request->input('boundaries_of_land') ?? 'Not Available',
            'plan_image' => $planImageName ?? 'Not Available',
            // Add more fields as needed
        ]);

        // Save the AcquiredEstate
        $acquiredEstate->save();

        // Optionally, you can delete the Non Acquired Estate if needed
        $nonEstate->delete();

        // You may return a response if needed
        return redirect()->route('estate.nonAcEstates')->with('success', 'Estate moved to Acquired Estate successfully!');
    }





    public function store(Request $request){
        $estate = new Estate();
    
        $estate->province = $request->input('province');
        $estate->district = $request->input('district');
        $estate->divisional_secretariat = $request->input('divisional_secretariat');
        $estate->grama_niladari_division = $request->input('grama_niladari_division');
        $estate->land_situated_village = $request->input('land_situated_village');
        $estate->acquired_land_name = $request->input('acquired_land_name');
        $estate->acquired_land_extent = $request->input('acquired_land_extent');
        $estate->total_extent_allotment_included = $request->input('total_extent_allotment_included');
        $estate->claimant_name_and_address = $request->input('claimant_name_and_address');
        $estate->office_file_recorded = $request->input('office_file_recorded');
        $estate->land_acquired_purpose = $request->input('land_acquired_purpose');
        $estate->land_acquisition_certificate = $request->input('land_acquisition_certificate');
        $estate->plan_availability = $request->input('plan_availability');
        $estate->plan_no_and_lot_no = $request->input('plan_no_and_lot_no') ?? 'Not Available';
        $estate->boundaries_of_land = $request->input('boundaries_of_land');
    
        if($request->hasFile('plan_image')){
            $plan_image = $request->file('plan_image');
            $extension = $plan_image->getClientOriginalExtension();
            $plan_img_name = time(). '.' . $extension;
            $plan_image->move('uploads/images/', $plan_img_name);
            $estate->plan_image = $plan_img_name;
        } else {
            $estate->plan_image = '';
        }
    
        if($request->hasFile('land_acquisition_certificate')){
            $land_acquisition_certificate = $request->file('land_acquisition_certificate');
            $extension = $land_acquisition_certificate->getClientOriginalExtension();
            $certificate_img_name = time(). '.' . $extension;
            $land_acquisition_certificate->move('uploads/images/', $certificate_img_name);
            $estate->land_acquisition_certificate = $certificate_img_name;
        } else {
            $estate->land_acquisition_certificate = '';
        }
    
            $estate->save();
        
            if ($estate->save()) {
                $status = 'success';
                $message = 'Estate details have been successfully added!';
            } else {
                $status = 'error';
                $message = 'Failed to add estate details. Please try again.';
            }
        
            return redirect()->route('estate')->with('status', $status)->with('message', $message);
    }
    

    public function viewEstate(){
        $estates = Estate::paginate(8);

        return view('estate.viewEstates')->with('estates', $estates);
    }

    public function filterResults(Request $request)
    {
        $provinces = Estate::distinct()->pluck('province');
        $districts = Estate::distinct()->pluck('district');
        $divisionalSecretariats = Estate::distinct()->pluck('divisional_secretariat');
        $gramaNiladariDivisions = Estate::distinct()->pluck('grama_niladari_division');

        $query = Estate::query();

        // Apply AND condition for filters
        $query->where(function ($query) use ($request) {
            if ($request->has('province')) {
                $query->where('province', $request->input('province'));
            }

            if ($request->has('district')) {
                $query->where('district', $request->input('district'));
            }

            if ($request->has('divisional_secretariat')) {
                $query->where('divisional_secretariat', $request->input('divisional_secretariat'));
            }

            if ($request->has('grama_niladari_division')) {
                $query->where('grama_niladari_division', $request->input('grama_niladari_division'));
            }
        });

        $estates = $query->paginate(10);

        return view('estate.viewEstates', compact('estates', 'provinces', 'districts', 'divisionalSecretariats', 'gramaNiladariDivisions'));
    }


    //get districts according to province
    public function getDistrictsByProvince(Request $request)
    {
        $province = $request->input('province');
        // Fetch districts based on $province (use your actual logic here)
        $districts = Estate::where('province', $province)->pluck('district')->unique()->toArray();

        return response()->json($districts);
    }

    //get divisional secretariat according to district
    public function getDivisionalSecretariatsByDistrict(Request $request)
    {
        $district = $request->input('district');
        // Fetch divisional secretariats based on $district (use your actual logic here)
        $divisionalSecretariats = Estate::where('district', $district)->pluck('divisional_secretariat')->unique()->toArray();

        return response()->json($divisionalSecretariats);
    }

    //get grama niladari division according to divisional secretariat
    public function getGramaNiladariDivisionsByDivisionalSecretariat(Request $request)
    {
        $divisionalSecretariat = $request->input('divisional_secretariat');
        // Fetch grama niladari divisions based on $divisionalSecretariat (use your actual logic here)
        $gramaNiladariDivisions = Estate::where('divisional_secretariat', $divisionalSecretariat)->pluck('grama_niladari_division')->unique()->toArray();

        return response()->json($gramaNiladariDivisions);
    }

     
    public function showData($id){
        $estate = Estate::findOrFail($id);

        return view('estate.fullDetails')->with('estate', $estate);

    }

    public function update(Request $request, $id)
        {
            try {
                $estate = Estate::findOrFail($id);

                // Update each field based on the input
                $estate->update([
                    'province' => $request->input('province'),
                    'district' => $request->input('district'),
                    'divisional_secretariat' => $request->input('divisional_secretariat'),
                    'grama_niladari_division' => $request->input('grama_niladari_division'),
                    'land_situated_village' => $request->input('land_situated_village'),
                    'acquired_land_name' => $request->input('acquired_land_name'),
                    'acquired_land_extent' => $request->input('acquired_land_extent'),
                    'total_extent_allotment_included' => $request->input('total_extent_allotment_included'),
                    'claimant_name_and_address' => $request->input('claimant_name_and_address'),
                    'office_file_recorded' => $request->input('office_file_recorded'),
                    'land_acquired_purpose' => $request->input('land_acquired_purpose'),
                    'plan_availability' => $request->input('plan_availability'),
                    'plan_no_and_lot_no' => $request->input('plan_no_and_lot_no'),
                    'boundaries_of_land' => $request->input('boundaries_of_land'),
                ]);

                // Update land_acquisition_certificate if a new file is provided
                if ($request->hasFile('land_acquisition_certificate')) {
                    $certificate_image = $request->file('land_acquisition_certificate');
                    $certificate_img_name = time() . '.' . $certificate_image->getClientOriginalExtension();
                    $certificate_image->move('uploads/images/', $certificate_img_name);
                    $estate->update(['land_acquisition_certificate' => $certificate_img_name]);
                }

                // Update plan_image if a new file is provided
                if ($request->hasFile('plan_image')) {
                    $plan_image = $request->file('plan_image');
                    $plan_img_name = time() . '.' . $plan_image->getClientOriginalExtension();
                    $plan_image->move('uploads/images/', $plan_img_name);
                    $estate->update(['plan_image' => $plan_img_name]);
                }

                // Redirect back to the estate details page with a success message
                return redirect()->back()->with('success', 'Estate details updated successfully!');
            } catch (\Exception $e) {
                // Redirect back with an error message if there's an exception
                return redirect()->back()->with('error', 'Error updating estate details. Please try again.');
            }
        }

        public function destroy($id)
        {
            try {
                $estate = Estate::findOrFail($id);
                $estate->delete();
    
                return redirect()->route('showEstates')->with(['status' => 'success', 'message' => 'Estate deleted successfully']);
            } catch (\Exception $e) {
                return redirect()->route('showEstates')->with(['status' => 'error', 'message' => 'Error deleting estate']);
            }
        }


        public function manageEstates()
        {
            return view('estate.manageEstates');
        }


        public function exportEstates(Request $request)
        {
            $filename = 'Estates_export.csv';

            $data = DB::table('estates')->get(); // or 'non_estates' for NonEstateController

            return new StreamedResponse(function () use ($data, $filename) {
                $output = fopen('php://output', 'w');

                // Output CSV header
                fputcsv($output, array_keys((array) $data[0]));

                // Output CSV data
                foreach ($data as $row) {
                    fputcsv($output, (array) $row);
                }

                fclose($output);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ]);
        }

        public function showReport()
        {
            return view('estate.reports');
        }

}
