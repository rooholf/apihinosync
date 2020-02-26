<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use App\Gnmstcustomer; //model
use App\Gnmstcustomerbank;
use App\Gnmstcustomerprofitcenter;
use App\Transformers\GnmstcustomerTransformer; //transformer
use Auth;


class GnmstcustomerController extends Controller
{
    public function show(Gnmstcustomer $gnmstcustomer, $id)
    {
    	$customer = $gnmstcustomer->find($id);
    	// dd($customer);
    	return fractal()
    			->item($customer)
    			->transformWith(new GnmstcustomerTransformer)
    			->toArray();
    }

    public function add(Request $request, Gnmstcustomer $gnmstcustomer)
    {
        $this->validate($request, [
            'CustomerCode' => 'required', 
        ]);

        $gnmstcustomer = $gnmstcustomer->create([
            'CompanyCode' => $request->CompanyCode, 
            'CustomerCode' => $request->CustomerCode, 
            'StandardCode' => $request->StandardCode, 
            'CustomerName' => $request->CustomerName, 
            'CustomerAbbrName' => $request->CustomerAbbrName, 
            'CustomerGovName' => $request->CustomerGovName, 
            'CustomerType' => $request->CustomerType, 
            'CategoryCode' => $request->CategoryCode, 
            'Address1' => $request->Address1, 
            'Address2' => $request->Address2, 
            'Address3' => $request->Address3, 
            'Address4' => $request->Address4, 
            'PhoneNo' => $request->PhoneNo, 
            'HPNo' => $request->HPNo, 
            'FaxNo' => $request->FaxNo, 
            'isPKP' => $request->isPKP, 
            'NPWPNo' => $request->NPWPNo, 
            'NPWPDate' => $request->NPWPDate, 
            'SKPNo' => $request->SKPNo, 
            'SKPDate' => $request->SKPDate, 
            'ProvinceCode' => $request->ProvinceCode, 
            'AreaCode' => $request->AreaCode, 
            'CityCode' => $request->CityCode, 
            'ZipNo' => $request->ZipNo, 
            'Status' => $request->Status, 
            'CreatedBy' => $request->CreatedBy, 
            'CreatedDate' => $request->CreatedDate, 
            'LastUpdateBy' => $request->LastUpdateBy, 
            'LastUpdateDate' => $request->LastUpdateDate, 
            'isLocked' => $request->isLocked, 
            'LockingBy' => $request->LockingBy, 
            'LockingDate' => $request->LockingDate, 
            'Email' => $request->Email, 
            'BirthDate' => $request->BirthDate, 
            'Spare01' => $request->Spare01, 
            'Spare02' => $request->Spare02, 
            'Spare03' => $request->Spare03, 
            'Spare04' => $request->Spare04, 
            'Spare05' => $request->Spare05, 
            'Gender' => $request->Gender, 
            'OfficePhoneNo' => $request->OfficePhoneNo, 
            'KelurahanDesa' => $request->KelurahanDesa, 
            'KecamatanDistrik' => $request->KecamatanDistrik, 
            'KotaKabupaten' => $request->KotaKabupaten, 
            'IbuKota' => $request->IbuKota, 
            'CustomerStatus' => $request->CustomerStatus,
        ]);

        return fractal()
            ->item($gnmstcustomer)
            ->transformWith(new SupplierTransformer)
            ->toArray();

    }

    public function update(Request $request, Gnmstcustomer $gnmstcustomer)
    {

        $gnmstcustomer->CompanyCode = $request->get('CompanyCode', $gnmstcustomer->CompanyCode);
        $gnmstcustomer->CustomerCode = $request->get('CustomerCode', $gnmstcustomer->CustomerCode);
        $gnmstcustomer->StandardCode = $request->get('StandardCode', $gnmstcustomer->StandardCode);
        $gnmstcustomer->CustomerName = $request->get('CustomerName', $gnmstcustomer->CustomerName);
        $gnmstcustomer->CustomerAbbrName = $request->get('CustomerAbbrName', $gnmstcustomer->CustomerAbbrName);
        $gnmstcustomer->CustomerGovName = $request->get('CustomerGovName', $gnmstcustomer->CustomerGovName);
        $gnmstcustomer->CustomerType = $request->get('CustomerType', $gnmstcustomer->CustomerType);
        $gnmstcustomer->CategoryCode = $request->get('CategoryCode', $gnmstcustomer->CategoryCode);
        $gnmstcustomer->Address1 = $request->get('Address1', $gnmstcustomer->Address1);
        $gnmstcustomer->Address2 = $request->get('Address2', $gnmstcustomer->Address2);
        $gnmstcustomer->Address3 = $request->get('Address3', $gnmstcustomer->Address3);
        $gnmstcustomer->Address4 = $request->get('Address4', $gnmstcustomer->Address4);
        $gnmstcustomer->PhoneNo = $request->get('PhoneNo', $gnmstcustomer->PhoneNo);
        $gnmstcustomer->HPNo = $request->get('HPNo', $gnmstcustomer->HPNo);
        $gnmstcustomer->FaxNo = $request->get('FaxNo', $gnmstcustomer->FaxNo);
        $gnmstcustomer->isPKP = $request->get('isPKP', $gnmstcustomer->isPKP);
        $gnmstcustomer->NPWPNo = $request->get('NPWPNo', $gnmstcustomer->NPWPNo);
        $gnmstcustomer->NPWPDate = $request->get('NPWPDate', $gnmstcustomer->NPWPDate);
        $gnmstcustomer->SKPNo = $request->get('SKPNo', $gnmstcustomer->SKPNo);
        $gnmstcustomer->SKPDate = $request->get('SKPDate', $gnmstcustomer->SKPDate);
        $gnmstcustomer->ProvinceCode = $request->get('ProvinceCode', $gnmstcustomer->ProvinceCode);
        $gnmstcustomer->AreaCode = $request->get('AreaCode', $gnmstcustomer->AreaCode);
        $gnmstcustomer->CityCode = $request->get('CityCode', $gnmstcustomer->CityCode);
        $gnmstcustomer->ZipNo = $request->get('ZipNo', $gnmstcustomer->ZipNo);
        $gnmstcustomer->Status = $request->get('Status', $gnmstcustomer->Status);
        $gnmstcustomer->CreatedBy = $request->get('CreatedBy', $gnmstcustomer->CreatedBy);
        $gnmstcustomer->CreatedDate = $request->get('CreatedDate', $gnmstcustomer->CreatedDate);
        $gnmstcustomer->LastUpdateBy = $request->get('LastUpdateBy', $gnmstcustomer->LastUpdateBy);
        $gnmstcustomer->LastUpdateDate = $request->get('LastUpdateDate', $gnmstcustomer->LastUpdateDate);
        $gnmstcustomer->isLocked = $request->get('isLocked', $gnmstcustomer->isLocked);
        $gnmstcustomer->LockingBy = $request->get('LockingBy', $gnmstcustomer->LockingBy);
        $gnmstcustomer->LockingDate = $request->get('LockingDate', $gnmstcustomer->LockingDate);
        $gnmstcustomer->Email = $request->get('Email', $gnmstcustomer->Email);
        $gnmstcustomer->BirthDate = $request->get('BirthDate', $gnmstcustomer->BirthDate);
        $gnmstcustomer->Spare01 = $request->get('Spare01', $gnmstcustomer->Spare01);
        $gnmstcustomer->Spare02 = $request->get('Spare02', $gnmstcustomer->Spare02);
        $gnmstcustomer->Spare03 = $request->get('Spare03', $gnmstcustomer->Spare03);
        $gnmstcustomer->Spare04 = $request->get('Spare04', $gnmstcustomer->Spare04);
        $gnmstcustomer->Spare05 = $request->get('Spare05', $gnmstcustomer->Spare05);
        $gnmstcustomer->Gender = $request->get('Gender', $gnmstcustomer->Gender);
        $gnmstcustomer->OfficePhoneNo = $request->get('OfficePhoneNo', $gnmstcustomer->OfficePhoneNo);
        $gnmstcustomer->KelurahanDesa = $request->get('KelurahanDesa', $gnmstcustomer->KelurahanDesa);        
        $gnmstcustomer->KecamatanDistrik = $request->get('KecamatanDistrik', $gnmstcustomer->KecamatanDistrik);
        $gnmstcustomer->KotaKabupaten = $request->get('KotaKabupaten', $gnmstcustomer->KotaKabupaten);
        $gnmstcustomer->IbuKota = $request->get('IbuKota', $gnmstcustomer->IbuKota);
        $gnmstcustomer->CustomerStatus = $request->get('CustomerStatus', $gnmstcustomer->CustomerStatus);

        $gnmstcustomer->save();

        return fractal()
            ->item($gnmstcustomer)
            ->transformWith(new SupplierTransformer)
            ->toArray();
    }
}
