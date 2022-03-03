<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function medicalno($medicalno)
    {
        $patient = Patient::where('MedicalNo', $medicalno)->first();
        if($patient == null) return JsonResponseController::jsonData(null, 'Data tidak ditemukan!');
        
        $data['PatientName'] = $patient->PatientName;
        $data['Since'] = $patient->Since;
        $data['GCBloodType'] = GeneralCodeController::parseBlood($patient->GCBloodType);
        $data['GCOccupation'] = GeneralCodeController::parseOccupation($patient->GCOccupation);
        $data['GCPatientCategory'] = GeneralCodeController::parsePatientCat($patient->GCPatientCategory);
        $data['MedicalNo'] = $patient->MedicalNo;
        $data['SSN'] = $patient->SSN;
        $data['MobilePhoneNo1'] = $patient->MobilePhoneNo1;
        $data['CityOfBirth'] = $patient->CityOfBirth;
        $data['DateOfBirth'] = Carbon::parse($patient->DateOfBirth)->format('d-m-Y');
        $data['GCSex'] = GeneralCodeController::parseSex($patient->GCSex);
        $data['GCEducation'] = GeneralCodeController::parseEducation($patient->GCEducation);
        $data['GCMaritalStatus'] = GeneralCodeController::parseMarital($patient->GCMaritalStatus);
        $data['GCNationality'] = GeneralCodeController::parseNationality($patient->GCNationality);
        $data['GCReligion'] = GeneralCodeController::parseReligion($patient->GCReligion);

        return JsonResponseController::jsonData($data, 'Data ditemukan!');
    }
}
