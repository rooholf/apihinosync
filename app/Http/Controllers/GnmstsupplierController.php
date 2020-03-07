<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB; // DB
	use Illuminate\Support\Str; //
	
	use App\Gnmstsupplier; 
	use App\Gnmstsupplierbank; 
	use App\Gnmstsupplierprofitcenter;
	use App\Transformers\GnmstsupplierTransformer; //transformer
	use Auth;

	use Carbon\Carbon;
	
	class GnmstsupplierController extends Controller
	{
		public function show(Request $request, Gnmstsupplier $gnmstsupplier)
		{
			$supplier = $gnmstsupplier->find($request->SupplierCode);

			// dd($supplier);

			if ($supplier) {
				return fractal()
					->item($supplier)
					->transformWith(new GnmstsupplierTransformer)
					->toArray();
			} else {

				return response()->json([
                    'data' => 0
                ], 200);
			}
			
				
		}

		public function add(Request $request, Gnmstsupplier $gnmstsupplier, Gnmstsupplierbank $gnmstsupplierbank)
		{
			$this->validate($request, [
            	'SupplierCode' => 'required', 
			]);
			
			$gnmstsupplier = $gnmstsupplier->create([
				'CompanyCode' => $request->CompanyCode,
				'SupplierCode' => $request->SupplierCode,
				'StandardCode' => $request->StandardCode,
				'SupplierName' => $request->SupplierName,
				'SupplierGovName' => $request->SupplierGovName,
				'Address1' => $request->Address1,
				'Address2' => $request->Address2,
				'Address3' => $request->Address3,
				'Address4' => $request->Address4,
				'PhoneNo' => $request->PhoneNo,
				'HPNo' => $request->HPNo,
				'FaxNo' => $request->FaxNo,
				'ProvinceCode' => $request->ProvinceCode,
				'AreaCode' => $request->AreaCode,
				'CityCode' => $request->CityCode,
				'ZipNo' => $request->ZipNo,
				'isPKP' => $request->isPKP,
				'NPWPNo' => $request->NPWPNo,
				'NPWPDate' => Carbon::now(),
				'Status' => $request->Status,
				'CreatedBy' => $request->CreatedBy,
				'CreatedDate' => Carbon::now(),
				'LastUpdateBy' => $request->LastUpdateBy,
				'LastUpdateDate' => Carbon::now(),
				'isLocked' => $request->isLocked,
				'LockingBy' => $request->LockingBy,
				'LockingDate' => Carbon::now(),
			]);

			$gnmstsupplierbank = $gnmstsupplierbank->create([
				'CompanyCode' => $request->CompanyCode, 
				'SupplierCode' => $request->SupplierCode,
				'BankCode' => $request->BankCode,
				'BankName' => $request->BankName,
				'AccountName' => $request->AccountName,
				'AccountBank' => $request->AccountBank,
				'CreatedBy' => $request->CreatedBy,
				'CreatedDate' => Carbon::now(),
				'LastUpdateBy' => $request->LastUpdateBy,
				'LastUpdateDate' => Carbon::now(),
				'isLocked' => $request->isLocked,
				'LockingBy' => $request->LockingBy,
				'LockingDate' => Carbon::now(),
			]);

			// $gnmstsupplierprofitcenter = $gnmstsupplierprofitcenter->create([
			// 	'CompanyCode' => $request->CompanyCode,
			// 	'BranchCode' => $request->BranchCode,
			// 	'SupplierCode' => $request->SupplierCode,
			// 	'ProfitCenterCode' => $request->ProfitCenterCode,
			// 	'ContactPerson' => $request->ContactPerson,
			// 	'SupplierClass' => $request->SupplierClass,
			// 	'SupplierGrade' => $request->SupplierGrade, 
			// 	'DiscPct' => $request->DiscPct,
			// 	'TOPCode' => $request->TOPCode,
			// 	'TaxCode' => $request->TaxCode,
			// 	'isBlackList' => $request->isBlackList,
			// 	'Status' => $request->Status,
			// 	'CreatedBy' => $request->CreatedBy,
			// 	'CreatedDate' => $request->CreatedDate,
			// 	'LastUpdateBy' => $request->LastUpdateBy,
			// 	'LastUpdateDate' => $request->LastUpdateDate,
			// ]);
			
			return fractal()
	            ->item($gnmstsupplier)
	            ->transformWith(new GnmstsupplierTransformer)
	            ->toArray();
			
		}
		
		public function update(Request $request, Gnmstsupplier $gnmstsupplier)
		{		
			$gnmstsupplier->CompanyCode = $request->get('CompanyCode', $gnmstsupplier->CompanyCode);
			$gnmstsupplier->SupplierCode = $request->get('SupplierCode', $gnmstsupplier->SupplierCode);
			$gnmstsupplier->StandardCode = $request->get('StandardCode', $gnmstsupplier->StandardCode);
			$gnmstsupplier->SupplierName = $request->get('SupplierName', $gnmstsupplier->SupplierName);
			$gnmstsupplier->SupplierGovName = $request->get('SupplierGovName', $gnmstsupplier->SupplierGovName);
			$gnmstsupplier->Address1 = $request->get('Address1', $gnmstsupplier->Address1);
			$gnmstsupplier->Address2 = $request->get('Address2', $gnmstsupplier->Address2);
			$gnmstsupplier->Address3 = $request->get('Address3', $gnmstsupplier->Address3);
			$gnmstsupplier->Address4 = $request->get('Address4', $gnmstsupplier->Address4);
			$gnmstsupplier->PhoneNo = $request->get('PhoneNo', $gnmstsupplier->PhoneNo);
			$gnmstsupplier->HPNo  = $request->get('HPNo', $gnmstsupplier->HPNo);
			$gnmstsupplier->FaxNo  = $request->get('FaxNo', $gnmstsupplier->FaxNo);
			$gnmstsupplier->ProvinceCode  = $request->get('ProvinceCode', $gnmstsupplier->ProvinceCode);
			$gnmstsupplier->AreaCode  = $request->get('AreaCode', $gnmstsupplier->AreaCode);
			$gnmstsupplier->CityCode  = $request->get('CityCode', $gnmstsupplier->CityCode);
			$gnmstsupplier->ZipNo  = $request->get('ZipNo', $gnmstsupplier->ZipNo);
			$gnmstsupplier->isPKP  = $request->get('isPKP', $gnmstsupplier->isPKP);
			$gnmstsupplier->NPWPNo  = $request->get('NPWPNo', $gnmstsupplier->NPWPNo);
			$gnmstsupplier->NPWPDate  = $request->get('NPWPDate', $gnmstsupplier->NPWPDate);
			$gnmstsupplier->Status  = $request->get('Status', $gnmstsupplier->Status);
			$gnmstsupplier->CreatedBy  = $request->get('CreatedBy', $gnmstsupplier->CreatedBy);
			$gnmstsupplier->CreatedDate  = $request->get('CreatedDate', $gnmstsupplier->CreatedDate);
			$gnmstsupplier->LastUpdateBy  = $request->get('LastUpdateBy', $gnmstsupplier->LastUpdateBy);
			$gnmstsupplier->LastUpdateDate  = $request->get('LastUpdateDate', $gnmstsupplier->LastUpdateDate);
			$gnmstsupplier->isLocked  = $request->get('isLocked', $gnmstsupplier->isLocked);
			$gnmstsupplier->LockingBy  = $request->get('LockingBy', $gnmstsupplier->LockingBy);
			$gnmstsupplier->LockingDate  = $request->get('LockingDate', $gnmstsupplier->LockingDate);
			$gnmstsupplier->save();

			$gnmstsupplierbank->CompanyCode = $request->get('CompanyCode', $gnmstsupplierbank->CompanyCode);
			$gnmstsupplierbank->SupplierCode = $request->get('SupplierCode', $gnmstsupplierbank->SupplierCode);
			$gnmstsupplierbank->BankCode = $request->get('BankCode', $gnmstsupplierbank->BankCode);
			$gnmstsupplierbank->BankName = $request->get('BankName', $gnmstsupplierbank->BankName);
			$gnmstsupplierbank->AccountName = $request->get('AccountName', $gnmstsupplierbank->AccountName);
			$gnmstsupplierbank->AccountBank = $request->get('AccountBank', $gnmstsupplierbank->AccountBank);
			$gnmstsupplierbank->CreatedBy = $request->get('CreatedBy', $gnmstsupplierbank->CreatedBy);
			$gnmstsupplierbank->CreatedDate = $request->get('CreatedDate', $gnmstsupplierbank->CreatedDate);
			$gnmstsupplierbank->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplierbank->LastUpdateBy);
			$gnmstsupplierbank->LastUpdateDate = $request->get('LastUpdateDate', $gnmstsupplierbank->LastUpdateDate);
			$gnmstsupplierbank->isLocked = $request->get('isLocked', $gnmstsupplierbank->isLocked);
			$gnmstsupplierbank->LockingBy = $request->get('LockingBy', $gnmstsupplierbank->LockingBy);
			$gnmstsupplierbank->LockingDate = $request->get('LockingDate', $gnmstsupplierbank->LockingDate);
			$gnmstsupplierbank->save();

			$gnmstsupplierprofitcenter->CompanyCode = $request->get('CompanyCode', $gnmstsupplierprofitcenter->CompanyCode);
			$gnmstsupplierprofitcenter->BranchCode = $request->get('BranchCode', $gnmstsupplierprofitcenter->BranchCode);
			$gnmstsupplierprofitcenter->SupplierCode = $request->get('SupplierCode', $gnmstsupplierprofitcenter->SupplierCode);
			$gnmstsupplierprofitcenter->ProfitCenterCode = $request->get('ProfitCenterCode', $gnmstsupplierprofitcenter->ProfitCenterCode);
			$gnmstsupplierprofitcenter->ContactPerson = $request->get('ContactPerson', $gnmstsupplierprofitcenter->ContactPerson);
			$gnmstsupplierprofitcenter->SupplierClass = $request->get('SupplierClass', $gnmstsupplierprofitcenter->SupplierClass);
			$gnmstsupplierprofitcenter->SupplierGrade = $request->get('SupplierGrade', $gnmstsupplierprofitcenter->SupplierGrade);
			$gnmstsupplierprofitcenter->DiscPct = $request->get('DiscPct', $gnmstsupplierprofitcenter->DiscPct);
			$gnmstsupplierprofitcenter->TOPCode = $request->get('TOPCode', $gnmstsupplierprofitcenter->TOPCode);
			$gnmstsupplierprofitcenter->TaxCode = $request->get('TaxCode', $gnmstsupplierprofitcenter->TaxCode);
			$gnmstsupplierprofitcenter->isBlackList = $request->get('isBlackList', $gnmstsupplierprofitcenter->isBlackList);
			$gnmstsupplierprofitcenter->Status = $request->get('Status', $gnmstsupplierprofitcenter->Status);
			$gnmstsupplierprofitcenter->CreatedBy = $request->get('CreatedBy', $gnmstsupplierprofitcenter->CreatedBy);
			$gnmstsupplierprofitcenter->CreatedDate = $request->get('CreatedDate', $gnmstsupplierprofitcenter->CreatedDate);
			$gnmstsupplierprofitcenter->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplierprofitcenter->LastUpdateBy);
			$gnmstsupplierprofitcenter->LastUpdateDate = $request->get('LastUpdateDate', $gnmstsupplierprofitcenter->LastUpdateDate);
			$gnmstsupplierprofitcenter->save();
			
			return fractal()
            ->item($gnmstcustomer)
            ->transformWith(new SupplierTransformer)
            ->toArray();
		}
	}
