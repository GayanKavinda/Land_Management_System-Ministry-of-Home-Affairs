<?php

// app/Http/Controllers/NonEstateController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;
use App\Models\NonEstate;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Dompdf\Dompdf;
use Dompdf\Options;


class NonEstateController extends Controller
{
    public function create()
    {
        return view('estate.nonAcEstates.create');
    }

    public function manageNonEstates (){
        return view('estate.nonAcEstates.manageNonEstates');
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');

        // Using Eloquent's where method with a closure for more flexibility
        $nonEstates = NonEstate::where(function ($query) use ($searchQuery) {
            $query->where('province', 'LIKE', "%$searchQuery%")
                ->orWhere('district', 'LIKE', "%$searchQuery%")
                ->orWhere('divisional_secretariat', 'LIKE', "%$searchQuery%")
                ->orWhere('grama_niladari_division', 'LIKE', "%$searchQuery%");
            // Add more conditions as needed
        })->get();

        // Pass the count directly to the view
        return view('estate.nonAcEstates.search', compact('nonEstates', 'searchQuery'))->with('resultCount', $nonEstates->count());
    }


    public function downloadPdf($searchQuery)
    {
        // Generate PDF content
        $pdfContent = $this->generatePdfContent($searchQuery);

        // Create a new Dompdf instance
        $options = new Options();
        $options->set('defaultFont', 'IskoolaPotaRegular');
        $dompdf = new Dompdf($options);

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
        return $pdfFilePath;
    }

    public function downloadAndProvidePdf($searchQuery)
    {
        $pdfFilePath = $this->downloadPdf($searchQuery);

        return response()->download($pdfFilePath)->deleteFileAfterSend(true);
    }

    private function generatePdfContent($searchQuery)
    {
        // Retrieve search results
        $nonEstates = NonEstate::where(function ($query) use ($searchQuery) {
            $query->where('province', 'LIKE', "%$searchQuery%")
                ->orWhere('district', 'LIKE', "%$searchQuery%")
                ->orWhere('divisional_secretariat', 'LIKE', "%$searchQuery%")
                ->orWhere('grama_niladari_division', 'LIKE', "%$searchQuery%");
        })->get();

        // Generate HTML table content for search results
        $html = '<table>';
        $html .= '<thead><tr>';
        $html .= '<th>Non Estate ID</th>';
        $html .= '<th>Province</th>';
        $html .= '<th>District</th>';
        $html .= '<th>Divisional Secretariat</th>';
        $html .= '<th>Grama Niladari Division</th>';
        $html .= '<th>Estate Name</th>';
        $html .= '<th>Plan No</th>';
        $html .= '<th>Land Extent</th>';
        $html .= '<th>Building Available</th>';
        $html .= '<th>Building Name</th>';
        $html .= '<th>Government Land</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';

        foreach ($nonEstates as $nonEstate) {
            $html .= '<tr>';
            $html .= '<td>' . $nonEstate->id . '</td>';
            $html .= '<td>' . $nonEstate->province . '</td>';
            $html .= '<td>' . $nonEstate->district . '</td>';
            $html .= '<td>' . $nonEstate->divisional_secretariat . '</td>';
            $html .= '<td>' . $nonEstate->grama_niladari_division . '</td>';
            $html .= '<td>' . $nonEstate->estate_name . '</td>';
            $html .= '<td>' . $nonEstate->plan_no . '</td>';
            $html .= '<td>' . $nonEstate->land_extent . '</td>';
            $html .= '<td>' . ($nonEstate->building_available ? 'Yes' : 'No') . '</td>';
            $html .= '<td>' . $nonEstate->building_name . '</td>';
            $html .= '<td>' . ($nonEstate->government_land ? 'Yes' : 'No') . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }


    public function store(Request $request)
    {
        // Validation rules can be adjusted based on your requirements
       $validatedData = $request->validate([
            'province' => 'required|string',
            'district' => 'required|string',
            'divisional_secretariat' => 'required|string',
            'grama_niladari_division' => 'required|string',
            'estate_name' => 'required|string',
            'plan_no' => 'required|string',
            'land_extent' => 'required|string',
            'building_available' => 'boolean',
            'building_name' => $request->input('building_available') ? 'required|string' : '',
            'government_land' => 'required|string',
            'reason' => $request->input('government_land') === 'cannot_specify' ? 'required|string' : 'nullable|string',
            // Add more fields as needed
        ]);

        // Create a new non-estate record in the database
        NonEstate::create($validatedData);

        // Redirect to a success page or perform other actions
        return redirect()->route('estate.nonAcEstates.create')->with('success', 'Non-Estate created successfully!');
    }

    public function view(Request $request)
    {
        $provinces = Nonestate::distinct()->pluck('province');
        $districts = Nonestate::distinct()->pluck('district');
        $divisionalSecretariats = Nonestate::distinct()->pluck('divisional_secretariat');
        $gramaNiladariDivisions = Nonestate::distinct()->pluck('grama_niladari_division');

        $query = Nonestate::query();

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

        $nonEstates = $query->paginate(10);

        return view('estate.nonAcEstates.view', compact('nonEstates', 'provinces', 'districts', 'divisionalSecretariats', 'gramaNiladariDivisions'));
    }



    // Function to get districts based on the selected province
    public function getDistrictsByProvince(Request $request)
    {
        $province = $request->input('province');
        $districts = NonEstate::where('province', $province)->distinct()->pluck('district')->toArray();

        return response()->json($districts);
    }

    // Function to get divisional secretariats based on the selected district
    public function getDivisionalSecretariatsByDistrict(Request $request)
    {
        $district = $request->input('district');
        $divisionalSecretariats = NonEstate::where('district', $district)->distinct()->pluck('divisional_secretariat')->toArray();

        return response()->json($divisionalSecretariats);
    }

    // Function to get grama niladari divisions based on the selected divisional secretariat
    public function getGramaNiladariDivisionsByDivisionalSecretariat(Request $request)
    {
        $divisionalSecretariat = $request->input('divisional_secretariat');
        $gramaNiladariDivisions = NonEstate::where('divisional_secretariat', $divisionalSecretariat)->distinct()->pluck('grama_niladari_division')->toArray();

        return response()->json($gramaNiladariDivisions);
    }




    public function edit($id)
    {
        $nonEstate = NonEstate::findOrFail($id);

        return view('estate.nonAcEstates.edit')->with('nonEstate', $nonEstate);
    }

    public function update(Request $request, $id)
    {
        try {
            $nonEstate = NonEstate::findOrFail($id);

            // Set the reason field based on the value of government_land
            $reason = $request->input('government_land') === 'yes' || $request->input('government_land') === 'no' ? null : $request->input('reason');

            // Update each field based on the input
            $nonEstate->update([
                'province' => $request->input('province'),
                'district' => $request->input('district'),
                'divisional_secretariat' => $request->input('divisional_secretariat'),
                'grama_niladari_division' => $request->input('grama_niladari_division'),
                'estate_name' => $request->input('estate_name'),
                'plan_no' => $request->input('plan_no'),
                'land_extent' => $request->input('land_extent'),
                'building_available' => $request->has('building_available') ? true : false,
                'building_name' => $request->input('building_name'),
                'government_land' => $request->input('government_land'),
                'reason' => $reason,
                // Add more fields as needed
            ]);

            // Redirect back to the non-estate details page with a success message
            return redirect()->back()->with('success', 'Non-Estate details updated successfully!');
        } catch (\Exception $e) {
            // Redirect back with an error message if there's an exception
            return redirect()->back()->with('error', 'Error updating non-estate details. Please try again.');
        }
    }



    public function destroy($id)
    {
        $nonEstate = NonEstate::findOrFail($id);


        // Add confirmation message to the session
        Session::flash('success', 'Non-Estate deleted successfully!');

        $nonEstate->delete();

        return redirect()->route('estate.nonAcEstates')->with('success', 'Non-Estate deleted successfully!');
    }




    public function exportNonEstates(Request $request)
        {
            $filename = 'Non_estates_export.csv';

            $data = DB::table('non_estates')->get(); // or 'non_estates' for NonEstateController

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




}

