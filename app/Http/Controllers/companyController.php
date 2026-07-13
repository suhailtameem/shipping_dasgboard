<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\company;
use Illuminate\Support\Facades\Session;

class companyController extends Controller
{
    /**
     * Retrieve the first company record.
     */
    public static function getCompany()
    {
        return company::first();
    }

    /**
     * Create or update company settings.
     */
    public function updateCompany(Request $request)
    {
        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'google_map_url' => 'nullable|url|max:255',
        ]);

        $company = company::first();

        $data = [
            'name_en' => $request->input('name_en'),
            'name_ar' => $request->input('name_ar'),
            'description_en' => $request->input('description_en'),
            'description_ar' => $request->input('description_ar'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'website' => $request->input('website'),
            'google_map_url' => $request->input('google_map_url'),
        ];

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $extension = $image->getClientOriginalExtension();
            $imageName = date('Y-m-d') . "-" . rand(10000, 99999) . "." . $extension;
            $destination = 'imgs/company';
            
            // Ensure public directory exists
            $destinationPath = public_path($destination);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            
            $image->move($destinationPath, $imageName);
            $data['logo'] = $destination . '/' . $imageName;
        }

        if ($company) {
            $updated = $company->update($data);
            $this->sessionStatus($updated);
        } else {
            $created = company::create($data);
            $this->sessionStatus($created);
        }

        return redirect()->back();
    }

    /**
     * Flash action status to session.
     */
    private function sessionStatus($success)
    {
        if ($success) {
            Session::flash('status', 'Company settings updated successfully.');
            Session::flash('stype', 'success');
        } else {
            Session::flash('status', 'Failed to update Company settings.');
            Session::flash('stype', 'danger');
        }
    }
}
