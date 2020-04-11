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
				// return fractal()
				// 	->item($supplier)
				// 	->transformWith(new GnmstsupplierTransformer)
				// 	->toArray();
				return response()->json([
                    'data' => 1
                ], 200);
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

			if (strlen($request->PhoneNo) > 15) {
				$PhoneNo = Str::limit($request->PhoneNo, 10, 'xxx');
			} else {
				$PhoneNo = $request->PhoneNo;
			}

			if (strlen($request->HPNo) > 15) {
				$HPNo = Str::limit($request->HPNo, 10, 'xxx');
			} else {
				$HPNo = $request->HPNo;
			}

			$supplier = $gnmstsupplier->find($request->SupplierCode);

			if (!$supplier) {
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
					'PhoneNo' => $PhoneNo,
					'HPNo' => $HPNo,
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

				$profitcenter = Gnmstsupplierprofitcenter::where('SupplierCode', $request->CustomerCode);
				if ($profitcenter->count() < 1) {
					$profit = [
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '000',
							'SupplierCode' => $request->SupplierCode,
							'ProfitCenterCode' => '200',
							'ContactPerson' => $HPNo,
							'SupplierClass' => 'HV2',
							'SupplierGrade' => 'A', 
							'DiscPct' => 0,
							'TOPCode' => 'C30',
							'TaxCode' => 'PPN',
							'isBlackList' => 'False',
							'Status' => 1,
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
						],
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '000',
							'SupplierCode' => $request->SupplierCode,
							'ProfitCenterCode' => '300',
							'ContactPerson' => $HPNo,
							'SupplierClass' => 'HV2-SP',
							'SupplierGrade' => 'A', 
							'DiscPct' => 0,
							'TOPCode' => 'C30',
							'TaxCode' => 'PPN',
							'isBlackList' => 'False',
							'Status' => 1,
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
						],
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '002',
							'SupplierCode' => $request->SupplierCode,
							'ProfitCenterCode' => '200',
							'ContactPerson' => $HPNo,
							'SupplierClass' => 'HV2',
							'SupplierGrade' => 'A', 
							'DiscPct' => 0,
							'TOPCode' => 'C30',
							'TaxCode' => 'PPN',
							'isBlackList' => 'False',
							'Status' => 1,
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
						],
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '002',
							'SupplierCode' => $request->SupplierCode,
							'ProfitCenterCode' => '300',
							'ContactPerson' => $HPNo,
							'SupplierClass' => 'HV2-SP',
							'SupplierGrade' => 'A', 
							'DiscPct' => 0,
							'TOPCode' => 'C30',
							'TaxCode' => 'PPN',
							'isBlackList' => 'False',
							'Status' => 1,
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
						]
					];

					Gnmstsupplierprofitcenter::insert($profit);
				}

				return response()->json([
                    'data' => 0
                ], 200);
			} else {
				return response()->json([
                    'data' => 1
                ], 200);
			}
			
			
			// return fractal()
	  //           ->item($gnmstsupplier)
	  //           ->transformWith(new GnmstsupplierTransformer)
	  //           ->toArray();
			
		}
		
		public function update(Request $request, Gnmstsupplier $gnmstsupplier, Gnmstsupplierbank $gnmstsupplierbank)
		{	

			if (strlen($request->PhoneNo) > 15) {
				$PhoneNo = Str::limit($request->PhoneNo, 10, 'xxx');
			} else {
				$PhoneNo = $request->PhoneNo;
			}

			if (strlen($request->HPNo) > 15) {
				$HPNo = Str::limit($request->HPNo, 10, 'xxx');
			} else {
				$HPNo = $request->HPNo;
			}

			$gnmstsupplier = Gnmstsupplier::find($request->SupplierCode);
			$gnmstsupplier->CompanyCode = $request->get('CompanyCode', $gnmstsupplier->CompanyCode);
			$gnmstsupplier->SupplierCode = $request->get('SupplierCode', $gnmstsupplier->SupplierCode);
			$gnmstsupplier->StandardCode = $request->get('StandardCode', $gnmstsupplier->StandardCode);
			$gnmstsupplier->SupplierName = $request->get('SupplierName', $gnmstsupplier->SupplierName);
			$gnmstsupplier->SupplierGovName = $request->get('SupplierGovName', $gnmstsupplier->SupplierGovName);
			$gnmstsupplier->Address1 = $request->get('Address1', $gnmstsupplier->Address1);
			$gnmstsupplier->Address2 = $request->get('Address2', $gnmstsupplier->Address2);
			$gnmstsupplier->Address3 = $request->get('Address3', $gnmstsupplier->Address3);
			$gnmstsupplier->Address4 = $request->get('Address4', $gnmstsupplier->Address4);
			$gnmstsupplier->PhoneNo = $PhoneNo;
			$gnmstsupplier->HPNo  = $HPNo;
			$gnmstsupplier->FaxNo  = $request->get('FaxNo', $gnmstsupplier->FaxNo);
			$gnmstsupplier->ProvinceCode  = $request->get('ProvinceCode', $gnmstsupplier->ProvinceCode);
			$gnmstsupplier->AreaCode  = $request->get('AreaCode', $gnmstsupplier->AreaCode);
			$gnmstsupplier->CityCode  = $request->get('CityCode', $gnmstsupplier->CityCode);
			$gnmstsupplier->ZipNo  = $request->get('ZipNo', $gnmstsupplier->ZipNo);
			$gnmstsupplier->isPKP  = $request->get('isPKP', $gnmstsupplier->isPKP);
			$gnmstsupplier->NPWPNo  = $request->get('NPWPNo', $gnmstsupplier->NPWPNo);
			$gnmstsupplier->NPWPDate  = $request->get('NPWPDate', Carbon::now());
			$gnmstsupplier->Status  = $request->get('Status', $gnmstsupplier->Status);
			$gnmstsupplier->LastUpdateBy  = $request->get('LastUpdateBy', $gnmstsupplier->LastUpdateBy);
			$gnmstsupplier->LastUpdateDate  = $request->get('LastUpdateDate', Carbon::now());
			$gnmstsupplier->isLocked  = $request->get('isLocked', $gnmstsupplier->isLocked);
			$gnmstsupplier->LockingBy  = $request->get('LockingBy', $gnmstsupplier->LockingBy);
			$gnmstsupplier->LockingDate  = $request->get('LockingDate', Carbon::now());
			$gnmstsupplier->save();

			$gnmstsupplierbank = Gnmstsupplierbank::find($request->SupplierCode);
			if ($gnmstsupplierbank) {
				$gnmstsupplierbank->CompanyCode = $request->get('CompanyCode', $gnmstsupplierbank->CompanyCode);
				$gnmstsupplierbank->SupplierCode = $request->get('SupplierCode', $gnmstsupplierbank->SupplierCode);
				$gnmstsupplierbank->BankCode = $request->get('BankCode', $gnmstsupplierbank->BankCode);
				$gnmstsupplierbank->BankName = $request->get('BankName', $gnmstsupplierbank->BankName);
				$gnmstsupplierbank->AccountName = $request->get('AccountName', $gnmstsupplierbank->AccountName);
				$gnmstsupplierbank->AccountBank = $request->get('AccountBank', $gnmstsupplierbank->AccountBank);
				$gnmstsupplierbank->CreatedBy = $request->get('CreatedBy', $gnmstsupplierbank->CreatedBy);
				$gnmstsupplierbank->CreatedDate = $request->get('CreatedDate', Carbon::now());
				$gnmstsupplierbank->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplierbank->LastUpdateBy);
				$gnmstsupplierbank->LastUpdateDate = $request->get('LastUpdateDate', Carbon::now());
				$gnmstsupplierbank->isLocked = $request->get('isLocked', $gnmstsupplierbank->isLocked);
				$gnmstsupplierbank->LockingBy = $request->get('LockingBy', $gnmstsupplierbank->LockingBy);
				$gnmstsupplierbank->LockingDate = $request->get('LockingDate', Carbon::now());
				$gnmstsupplierbank->save();
			} else {
				Gnmstsupplierbank::create([
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
			}
				

			$profitcenter = Gnmstsupplierprofitcenter::where('SupplierCode', $request->SupplierCode)->delete();

			$profit = [
				[
					'CompanyCode' => $request->CompanyCode,
					'BranchCode' => '000',
					'SupplierCode' => $request->SupplierCode,
					'ProfitCenterCode' => '200',
					'ContactPerson' => $HPNo,
					'SupplierClass' => 'HV2',
					'SupplierGrade' => 'A', 
					'DiscPct' => 0,
					'TOPCode' => 'C30',
					'TaxCode' => 'PPN',
					'isBlackList' => 'False',
					'Status' => 1,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
				],
				[
					'CompanyCode' => $request->CompanyCode,
					'BranchCode' => '000',
					'SupplierCode' => $request->SupplierCode,
					'ProfitCenterCode' => '300',
					'ContactPerson' => $HPNo,
					'SupplierClass' => 'HV2-SP',
					'SupplierGrade' => 'A', 
					'DiscPct' => 0,
					'TOPCode' => 'C30',
					'TaxCode' => 'PPN',
					'isBlackList' => 'False',
					'Status' => 1,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
				],
				[
					'CompanyCode' => $request->CompanyCode,
					'BranchCode' => '002',
					'SupplierCode' => $request->SupplierCode,
					'ProfitCenterCode' => '200',
					'ContactPerson' => $HPNo,
					'SupplierClass' => 'HV2',
					'SupplierGrade' => 'A', 
					'DiscPct' => 0,
					'TOPCode' => 'C30',
					'TaxCode' => 'PPN',
					'isBlackList' => 'False',
					'Status' => 1,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
				],
				[
					'CompanyCode' => $request->CompanyCode,
					'BranchCode' => '002',
					'SupplierCode' => $request->SupplierCode,
					'ProfitCenterCode' => '300',
					'ContactPerson' => $HPNo,
					'SupplierClass' => 'HV2-SP',
					'SupplierGrade' => 'A', 
					'DiscPct' => 0,
					'TOPCode' => 'C30',
					'TaxCode' => 'PPN',
					'isBlackList' => 'False',
					'Status' => 1,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
				]
			];

			Gnmstsupplierprofitcenter::insert($profit);
			
			return fractal()
	            ->item($gnmstsupplier)
	            ->transformWith(new GnmstsupplierTransformer)
	            ->toArray();
		}
	}
