<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB; // DB
	use Illuminate\Support\Str; //
	
	use App\Gnmstsupplier; //model
	use App\Transformers\GnmstsupplierTransformer; //transformer
	use Auth;

	use Carbon\Carbon;
	
	
	class GnmstsupplierController extends Controller
	{
		public function show(Request $request, Gnmstsupplier $gnmstsupplier)
		{
			$supplier = $gnmstsupplier->find($request->SupplierCode);

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


		
		public function add(Request $request, Gnmstsupplier $gnmstsupplier)
		{
			$this->validate($request, [
				'SupplierCode' => 'required', 
			]);

			$supplier = $gnmstsupplier->find($request->SupplierCode);


			if (!$supplier) {
				$gnmstsupplier = $gnmstsupplier->updateOrInsert([
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
					'isPKP' => $request->isPKP, 
					'NPWPNo' => $request->NPWPNo, 
					// 'NPWPDate' => Carbon::create($request->NPWPDate, 'Asia/Jakarta'),
					'NPWPDate' => Carbon::now(), 
					'ProvinceCode' => '-', 
					'AreaCode' => '-', 
					'CityCode' => '-', 
					'ZipNo' => $request->ZipNo, 
					'Status' => $request->Status, 
					'CreatedBy' => $request->CreatedBy, 
					'CreatedDate' => Carbon::now(), 
					'LastUpdateBy' => $request->LastUpdateBy, 
					'LastUpdateDate' => Carbon::now(), 
					'isLocked' => $request->isLocked, 
					'LockingBy' => $request->LockingBy, 
					// 'LockingDate' => Carbon::create($request->LockingDate, 'Asia/Jakarta'), 
					'LockingDate' => Carbon::now(), 
					
				]);

				if ($gnmstsupplier) {
					$supplierbank = Gnmstsupplierbank::where('SupplierCode', $request->SupplierCode);

					if ($supplierbank == null) {
						$gnmstsupplierbank = $gnmstsupplierbank->firstOrCreate([
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
								

					$profitcenter = Gnmstsupplierprofitcenter::where('SupplierCode', $request->SupplierCode);

					if ($profitcenter->count() < 1) {
						$profit = [
							[
								'CompanyCode' => $request->CompanyCode,
								'BranchCode' => '1000',
								'SupplierCode' => $request->SupplierCode,
								'ProfitCenterCode' => '200',
								'SupplierClass' => 'HV2', 
								'TaxCode' => 'PPN11',
								'DiscPct' => 0,
								'TOPCode' => 'C30',
								'SupplierGrade' => 'A',
								'ContactPerson' => $request->PhoneNo,
								'isBlackList' => 'False',
								'CreatedBy' => $request->CreatedBy,
								'CreatedDate' => Carbon::now(),
								'LastUpdateBy' => $request->LastUpdateBy,
								'LastUpdateDate' => Carbon::now(),
							],
							[
								'CompanyCode' => $request->CompanyCode,
								'BranchCode' => '1000',
								'SupplierCode' => $request->SupplierCode,
								'ProfitCenterCode' => '300',
								'SupplierClass' => 'HV2-SP', 
								'TaxCode' => 'PPN11',
								'DiscPct' => 0,
								'TOPCode' => 'C30',
								'SupplierGrade' => 'A',
								'ContactPerson' => $request->PhoneNo,
								'isBlackList' => 'False',
								'CreatedBy' => $request->CreatedBy,
								'CreatedDate' => Carbon::now(),
								'LastUpdateBy' => $request->LastUpdateBy,
								'LastUpdateDate' => Carbon::now(),
							],
							[
								'CompanyCode' => $request->CompanyCode,
								'BranchCode' => '1002',
								'SupplierCode' => $request->SupplierCode,
								'ProfitCenterCode' => '200',
								'SupplierClass' => 'HV2', 
								'TaxCode' => 'PPN11',
								'DiscPct' => 0,
								'TOPCode' => 'C30',
								'SupplierGrade' => 'A',
								'ContactPerson' => $request->PhoneNo,
								'isBlackList' => 'False',
								'CreatedBy' => $request->CreatedBy,
								'CreatedDate' => Carbon::now(),
								'LastUpdateBy' => $request->LastUpdateBy,
								'LastUpdateDate' => Carbon::now(),
							],
							[
								'CompanyCode' => $request->CompanyCode,
								'BranchCode' => '1002',
								'SupplierCode' => $request->SupplierCode,
								'ProfitCenterCode' => '300',
								'SupplierClass' => 'HV2-SP', 
								'TaxCode' => 'PPN11',
								'DiscPct' => 0,
								'TOPCode' => 'C30',
								'SupplierGrade' => 'A',
								'ContactPerson' => $request->PhoneNo,
								'isBlackList' => 'False',
								'CreatedBy' => $request->CreatedBy,
								'CreatedDate' => Carbon::now(),
								'LastUpdateBy' => $request->LastUpdateBy,
								'LastUpdateDate' => Carbon::now(),
							]
						];

						Gnmstsupplierprofitcenter::insert($profit);
					}
				}

				return response()->json([
                    'data' => 0
                ], 200);
			} else {
				$gnmstsupplier = Gnmstsupplier::find($request->SupplierCode);
				$gnmstsupplier->CompanyCode = $request->get('CompanyCode', $gnmstsupplier->CompanyCode);
				$gnmstsupplier->StandardCode = $request->get('StandardCode', $gnmstsupplier->StandardCode);
				$gnmstsupplier->SupplierName = $request->get('SupplierName', $gnmstsupplier->SupplierName);
				$gnmstsupplier->SupplierGovName = $request->get('SupplierGovName', $gnmstsupplier->SupplierGovName);
				$gnmstsupplier->Address1 = $request->get('Address1', $gnmstsupplier->Address1);
				$gnmstsupplier->Address2 = $request->get('Address2', $gnmstsupplier->Address2);
				$gnmstsupplier->Address3 = $request->get('Address3', $gnmstsupplier->Address3);
				$gnmstsupplier->Address4 = $request->get('Address4', $gnmstsupplier->Address4);
				$gnmstsupplier->PhoneNo = $request->get('PhoneNo', $gnmstsupplier->PhoneNo);
				$gnmstsupplier->HPNo = $request->get('HPNo', $gnmstsupplier->HPNo);
				$gnmstsupplier->FaxNo = $request->get('FaxNo', $gnmstsupplier->FaxNo);
				$gnmstsupplier->isPKP = $request->get('isPKP', $gnmstsupplier->isPKP);
				$gnmstsupplier->NPWPNo = $request->get('NPWPNo', $gnmstsupplier->NPWPNo);
				$gnmstsupplier->ProvinceCode = $request->get('ProvinceCode', $gnmstsupplier->ProvinceCode);
				$gnmstsupplier->AreaCode = $request->get('AreaCode', $gnmstsupplier->AreaCode);
				$gnmstsupplier->CityCode = $request->get('CityCode', $gnmstsupplier->CityCode);
				$gnmstsupplier->ZipNo = $request->get('ZipNo', $gnmstsupplier->ZipNo);
				$gnmstsupplier->Status = $request->get('Status', $gnmstsupplier->Status);
				$gnmstsupplier->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplier->LastUpdateBy);
				$gnmstsupplier->isLocked = $request->get('isLocked', $gnmstsupplier->isLocked);
				$gnmstsupplier->LockingBy = $request->get('LockingBy', $gnmstsupplier->LockingBy);
				$gnmstsupplier->save();

				return response()->json([
                    'data' => 1
                ], 200);
			}
			
				


			// return fractal()
	  //           ->item($gnmstsupplier)
	  //           ->transformWith(new GnmstsupplierTransformer)
	  //           ->toArray();
			
		}
		
		// public function update(Request $request, Gnmstsupplier $gnmstsupplier)
		// {
			
		// 	$gnmstsupplier->CompanyCode = $request->get('CompanyCode', $gnmstsupplier->CompanyCode);
		// 	$gnmstsupplier->supplierCode = $request->get('supplierCode', $gnmstsupplier->supplierCode);
		// 	$gnmstsupplier->StandardCode = $request->get('StandardCode', $gnmstsupplier->StandardCode);
		// 	$gnmstsupplier->SupplierName = $request->get('SupplierName', $gnmstsupplier->SupplierName);
		// 	$gnmstsupplier->supplierAbbrName = $request->get('supplierAbbrName', $gnmstsupplier->supplierAbbrName);
		// 	$gnmstsupplier->SupplierGovName = $request->get('SupplierGovName', $gnmstsupplier->SupplierGovName);
		// 	$gnmstsupplier->SupplierType = $request->get('SupplierType', $gnmstsupplier->SupplierType);
		// 	$gnmstsupplier->CategoryCode = $request->get('CategoryCode', $gnmstsupplier->CategoryCode);
		// 	$gnmstsupplier->Address1 = $request->get('Address1', $gnmstsupplier->Address1);
		// 	$gnmstsupplier->Address2 = $request->get('Address2', $gnmstsupplier->Address2);
		// 	$gnmstsupplier->Address3 = $request->get('Address3', $gnmstsupplier->Address3);
		// 	$gnmstsupplier->Address4 = $request->get('Address4', $gnmstsupplier->Address4);
		// 	$gnmstsupplier->PhoneNo = $request->get('PhoneNo', $gnmstsupplier->PhoneNo);
		// 	$gnmstsupplier->HPNo = $request->get('HPNo', $gnmstsupplier->HPNo);
		// 	$gnmstsupplier->FaxNo = $request->get('FaxNo', $gnmstsupplier->FaxNo);
		// 	$gnmstsupplier->isPKP = $request->get('isPKP', $gnmstsupplier->isPKP);
		// 	$gnmstsupplier->NPWPNo = $request->get('NPWPNo', $gnmstsupplier->NPWPNo);
		// 	$gnmstsupplier->NPWPDate = $request->get('NPWPDate', $gnmstsupplier->NPWPDate);
		// 	$gnmstsupplier->SKPNo = $request->get('SKPNo', $gnmstsupplier->SKPNo);
		// 	$gnmstsupplier->SKPDate = $request->get('SKPDate', $gnmstsupplier->SKPDate);
		// 	$gnmstsupplier->ProvinceCode = $request->get('ProvinceCode', $gnmstsupplier->ProvinceCode);
		// 	$gnmstsupplier->AreaCode = $request->get('AreaCode', $gnmstsupplier->AreaCode);
		// 	$gnmstsupplier->CityCode = $request->get('CityCode', $gnmstsupplier->CityCode);
		// 	$gnmstsupplier->ZipNo = $request->get('ZipNo', $gnmstsupplier->ZipNo);
		// 	$gnmstsupplier->Status = $request->get('Status', $gnmstsupplier->Status);
		// 	$gnmstsupplier->CreatedBy = $request->get('CreatedBy', $gnmstsupplier->CreatedBy);
		// 	$gnmstsupplier->CreatedDate = $request->get('CreatedDate', $gnmstsupplier->CreatedDate);
		// 	$gnmstsupplier->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplier->LastUpdateBy);
		// 	$gnmstsupplier->LastUpdateDate = $request->get('LastUpdateDate', $gnmstsupplier->LastUpdateDate);
		// 	$gnmstsupplier->isLocked = $request->get('isLocked', $gnmstsupplier->isLocked);
		// 	$gnmstsupplier->LockingBy = $request->get('LockingBy', $gnmstsupplier->LockingBy);
		// 	$gnmstsupplier->LockingDate = $request->get('LockingDate', $gnmstsupplier->LockingDate);
		// 	$gnmstsupplier->Email = $request->get('Email', $gnmstsupplier->Email);
		// 	$gnmstsupplier->BirthDate = $request->get('BirthDate', $gnmstsupplier->BirthDate);
		// 	$gnmstsupplier->Spare01 = $request->get('Spare01', $gnmstsupplier->Spare01);
		// 	$gnmstsupplier->Spare02 = $request->get('Spare02', $gnmstsupplier->Spare02);
		// 	$gnmstsupplier->Spare03 = $request->get('Spare03', $gnmstsupplier->Spare03);
		// 	$gnmstsupplier->Spare04 = $request->get('Spare04', $gnmstsupplier->Spare04);
		// 	$gnmstsupplier->Spare05 = $request->get('Spare05', $gnmstsupplier->Spare05);
		// 	$gnmstsupplier->Gender = $request->get('Gender', $gnmstsupplier->Gender);
		// 	$gnmstsupplier->OfficePhoneNo = $request->get('OfficePhoneNo', $gnmstsupplier->OfficePhoneNo);
		// 	$gnmstsupplier->KelurahanDesa = $request->get('KelurahanDesa', $gnmstsupplier->KelurahanDesa);        
		// 	$gnmstsupplier->KecamatanDistrik = $request->get('KecamatanDistrik', $gnmstsupplier->KecamatanDistrik);
		// 	$gnmstsupplier->KotaKabupaten = $request->get('KotaKabupaten', $gnmstsupplier->KotaKabupaten);
		// 	$gnmstsupplier->IbuKota = $request->get('IbuKota', $gnmstsupplier->IbuKota);
		// 	$gnmstsupplier->supplierStatus = $request->get('supplierStatus', $gnmstsupplier->supplierStatus);
		// 	$gnmstsupplier->save();

		// 	$gnmstsupplierbank->CompanyCode = $request->get('CompanyCode', $gnmstsupplierbank->CompanyCode);
		// 	$gnmstsupplierbank->supplierCode = $request->get('supplierCode', $gnmstsupplierbank->supplierCode);
		// 	$gnmstsupplierbank->BankCode = $request->get('BankCode', $gnmstsupplierbank->BankCode);
		// 	$gnmstsupplierbank->BankName = $request->get('BankName', $gnmstsupplierbank->BankName);
		// 	$gnmstsupplierbank->AccountName = $request->get('AccountName', $gnmstsupplierbank->AccountName);
		// 	$gnmstsupplierbank->AccountBank = $request->get('AccountBank', $gnmstsupplierbank->AccountBank);
		// 	$gnmstsupplierbank->CreatedBy = $request->get('CreatedBy', $gnmstsupplierbank->CreatedBy);
		// 	$gnmstsupplierbank->CreatedDate = $request->get('CreatedDate', $gnmstsupplierbank->CreatedDate);
		// 	$gnmstsupplierbank->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplierbank->LastUpdateBy);
		// 	$gnmstsupplierbank->LastUpdateDate = $request->get('LastUpdateDate', $gnmstsupplierbank->LastUpdateDate);
		// 	$gnmstsupplierbank->isLocked = $request->get('isLocked', $gnmstsupplierbank->isLocked);
		// 	$gnmstsupplierbank->LockingBy = $request->get('LockingBy', $gnmstsupplierbank->LockingBy);
		// 	$gnmstsupplierbank->LockingDate = $request->get('LockingDate', $gnmstsupplierbank->LockingDate);
		// 	$gnmstsupplierbank->save();
		
		// 	$gnmstsupplierprofitcenter->CompanyCode = $request->get('CompanyCode', $gnmstsupplierprofitcenter->CompanyCode);
		// 	$gnmstsupplierprofitcenter->BranchCode = $request->get('BranchCode', $gnmstsupplierprofitcenter->BranchCode);
		// 	$gnmstsupplierprofitcenter->supplierCode = $request->get('supplierCode', $gnmstsupplierprofitcenter->supplierCode);
		// 	$gnmstsupplierprofitcenter->ProfitCenterCode = $request->get('ProfitCenterCode', $gnmstsupplierprofitcenter->ProfitCenterCode);
		// 	$gnmstsupplierprofitcenter->CreditLimit = $request->get('CreditLimit', $gnmstsupplierprofitcenter->CreditLimit);
		// 	$gnmstsupplierprofitcenter->PaymentCode = $request->get('PaymentCode', $gnmstsupplierprofitcenter->PaymentCode);
		// 	$gnmstsupplierprofitcenter->supplierClass = $request->get('SupplierClass', $gnmstsupplierprofitcenter->supplierClass);
		// 	$gnmstsupplierprofitcenter->TaxCode = $request->get('TaxCode', $gnmstsupplierprofitcenter->TaxCode);
		// 	$gnmstsupplierprofitcenter->TaxTransCode = $request->get('TaxTransCode', $gnmstsupplierprofitcenter->TaxTransCode);
		// 	$gnmstsupplierprofitcenter->DiscPct = $request->get('DiscPct', $gnmstsupplierprofitcenter->DiscPct);
		// 	$gnmstsupplierprofitcenter->LaborDiscPct = $request->get('LaborDiscPct', $gnmstsupplierprofitcenter->LaborDiscPct);
		// 	$gnmstsupplierprofitcenter->PartDiscPct = $request->get('PartDiscPct', $gnmstsupplierprofitcenter->PartDiscPct);
		// 	$gnmstsupplierprofitcenter->MaterialDiscPct = $request->get('MaterialDiscPct', $gnmstsupplierprofitcenter->MaterialDiscPct);
		// 	$gnmstsupplierprofitcenter->TOPCode = $request->get('TOPCode', $gnmstsupplierprofitcenter->TOPCode);
		// 	$gnmstsupplierprofitcenter->supplierGrade = $request->get('SupplierGrade', $gnmstsupplierprofitcenter->supplierGrade);
		// 	$gnmstsupplierprofitcenter->ContactPerson = $request->get('ContactPerson', $gnmstsupplierprofitcenter->ContactPerson);
		// 	$gnmstsupplierprofitcenter->CollectorCode = $request->get('CollectorCode', $gnmstsupplierprofitcenter->CollectorCode);
		// 	$gnmstsupplierprofitcenter->GroupPriceCode = $request->get('GroupPriceCode', $gnmstsupplierprofitcenter->GroupPriceCode);
		// 	$gnmstsupplierprofitcenter->isOverDueAllowed = $request->get('isOverDueAllowed', $gnmstsupplierprofitcenter->isOverDueAllowed);
		// 	$gnmstsupplierprofitcenter->SalesCode = $request->get('SalesCode', $gnmstsupplierprofitcenter->SalesCode);
		// 	$gnmstsupplierprofitcenter->SalesType = $request->get('SalesType', $gnmstsupplierprofitcenter->SalesType);
		// 	$gnmstsupplierprofitcenter->Salesman = $request->get('Salesman', $gnmstsupplierprofitcenter->Salesman);
		// 	$gnmstsupplierprofitcenter->isBlackList = $request->get('isBlackList', $gnmstsupplierprofitcenter->isBlackList);
		// 	$gnmstsupplierprofitcenter->CreatedBy = $request->get('CreatedBy', $gnmstsupplierprofitcenter->CreatedBy);
		// 	$gnmstsupplierprofitcenter->CreatedDate = $request->get('CreatedDate', $gnmstsupplierprofitcenter->CreatedDate);
		// 	$gnmstsupplierprofitcenter->LastUpdateBy = $request->get('LastUpdateBy', $gnmstsupplierprofitcenter->LastUpdateBy);
		// 	$gnmstsupplierprofitcenter->LastUpdateDate = $request->get('LastUpdateDate', $gnmstsupplierprofitcenter->LastUpdateDate);
		// 	$gnmstsupplierprofitcenter->isLocked = $request->get('isLocked', $gnmstsupplierprofitcenter->isLocked);
		// 	$gnmstsupplierprofitcenter->LockingBy = $request->get('LockingBy', $gnmstsupplierprofitcenter->LockingBy);
		// 	$gnmstsupplierprofitcenter->LockingDate = $request->get('LockingDate', $gnmstsupplierprofitcenter->LockingDate);
		// 	$gnmstsupplierbank->save();

		// 	return fractal()
  //           ->item($gnmstsupplier)
  //           ->transformWith(new SupplierTransformer)
  //           ->toArray();
		// }
	}
