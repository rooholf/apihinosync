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

	use Carbon\Carbon;
	
	
	class GnmstcustomerController extends Controller
	{
		public function show(Request $request, Gnmstcustomer $gnmstcustomer)
		{
			$customer = $gnmstcustomer->find($request->CustomerCode);

			if ($customer) {
				return fractal()
					->item($customer)
					->transformWith(new GnmstcustomerTransformer)
					->toArray();
			} else {

				return response()->json([
                    'data' => 0
                ], 200);
			}
			// dd($customer);
				
		}
		
		public function add(Request $request, Gnmstcustomer $gnmstcustomer, Gnmstcustomerbank $gnmstcustomerbank, Gnmstcustomerprofitcenter $gnmstcustomerprofitcenter)
		{
			$this->validate($request, [
				'CustomerCode' => 'required', 
			]);
			
			$gnmstcustomer = $gnmstcustomer->firstOrCreate([
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
				// 'NPWPDate' => Carbon::create($request->NPWPDate, 'Asia/Jakarta'),
				'NPWPDate' => Carbon::now(), 
				'SKPNo' => $request->SKPNo, 
				// 'SKPDate' => Carbon::create($request->SKPDate, 'Asia/Jakarta'), 
				'SKPDate' => Carbon::now(), 
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
				'Email' => $request->Email, 
				'BirthDate' => Carbon::now(), 
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

			if ($gnmstcustomer) {
				$customerbank = Gnmstcustomerbank::where('CustomerCode', $request->CustomerCode);

			if (!$customerbank) {
				$gnmstcustomerbank = $gnmstcustomerbank->firstOrCreate([
						'CompanyCode' => $request->CompanyCode, 
						'CustomerCode' => $request->CustomerCode,
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
							

				$profitcenter = Gnmstcustomerprofitcenter::where('CustomerCode', $request->CustomerCode);

				if ($profitcenter->count() < 1) {
					$profit = [
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '000',
							'CustomerCode' => $request->CustomerCode,
							'ProfitCenterCode' => '200',
							'CreditLimit' => 0,
							'PaymentCode' => 'CR',
							'CustomerClass' => 'HV2', 
							'TaxCode' => 'PPN',
							'TaxTransCode' => '01', 
							'DiscPct' => 0,
							'LaborDiscPct' => 0,
							'PartDiscPct' => 0,
							'MaterialDiscPct' => 0,
							'TOPCode' => 'C30',
							'CustomerGrade' => 'A',
							'ContactPerson' => $request->PhoneNo,
							'CollectorCode' => '-',
							'GroupPriceCode' => 'COMP',
							'isOverDueAllowed' => 'False',
							'SalesCode' => '000',
							'SalesType' => '1',
							'Salesman' => $request->CreatedBy,
							'isBlackList' => 'False',
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
							'isLocked' => $request->isLocked,
							'LockingBy' => $request->LockingBy,
							'LockingDate' => Carbon::now(),
						],
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '000',
							'CustomerCode' => $request->CustomerCode,
							'ProfitCenterCode' => '300',
							'CreditLimit' => 0,
							'PaymentCode' => 'CR',
							'CustomerClass' => 'HV2-SP', 
							'TaxCode' => 'PPN',
							'TaxTransCode' => '01', 
							'DiscPct' => 0,
							'LaborDiscPct' => 0,
							'PartDiscPct' => 0,
							'MaterialDiscPct' => 0,
							'TOPCode' => 'C30',
							'CustomerGrade' => 'A',
							'ContactPerson' => $request->PhoneNo,
							'CollectorCode' => '-',
							'GroupPriceCode' => 'COMP',
							'isOverDueAllowed' => 'False',
							'SalesCode' => '000',
							'SalesType' => '1',
							'Salesman' => $request->CreatedBy,
							'isBlackList' => 'False',
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
							'isLocked' => $request->isLocked,
							'LockingBy' => $request->LockingBy,
							'LockingDate' => Carbon::now(),
						],
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '002',
							'CustomerCode' => $request->CustomerCode,
							'ProfitCenterCode' => '200',
							'CreditLimit' => 0,
							'PaymentCode' => 'CR',
							'CustomerClass' => 'HV2', 
							'TaxCode' => 'PPN',
							'TaxTransCode' => '01', 
							'DiscPct' => 0,
							'LaborDiscPct' => 0,
							'PartDiscPct' => 0,
							'MaterialDiscPct' => 0,
							'TOPCode' => 'C30',
							'CustomerGrade' => 'A',
							'ContactPerson' => $request->PhoneNo,
							'CollectorCode' => '-',
							'GroupPriceCode' => 'COMP',
							'isOverDueAllowed' => 'False',
							'SalesCode' => '002',
							'SalesType' => '1',
							'Salesman' => $request->CreatedBy,
							'isBlackList' => 'False',
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
							'isLocked' => $request->isLocked,
							'LockingBy' => $request->LockingBy,
							'LockingDate' => Carbon::now(),
						],
						[
							'CompanyCode' => $request->CompanyCode,
							'BranchCode' => '002',
							'CustomerCode' => $request->CustomerCode,
							'ProfitCenterCode' => '300',
							'CreditLimit' => 0,
							'PaymentCode' => 'CR',
							'CustomerClass' => 'HV2-SP', 
							'TaxCode' => 'PPN',
							'TaxTransCode' => '01', 
							'DiscPct' => 0,
							'LaborDiscPct' => 0,
							'PartDiscPct' => 0,
							'MaterialDiscPct' => 0,
							'TOPCode' => 'C30',
							'CustomerGrade' => 'A',
							'ContactPerson' => $request->PhoneNo,
							'CollectorCode' => '-',
							'GroupPriceCode' => 'COMP',
							'isOverDueAllowed' => 'False',
							'SalesCode' => '002',
							'SalesType' => '1',
							'Salesman' => $request->CreatedBy,
							'isBlackList' => 'False',
							'CreatedBy' => $request->CreatedBy,
							'CreatedDate' => Carbon::now(),
							'LastUpdateBy' => $request->LastUpdateBy,
							'LastUpdateDate' => Carbon::now(),
							'isLocked' => $request->isLocked,
							'LockingBy' => $request->LockingBy,
							'LockingDate' => Carbon::now(),
						]
					];

					Gnmstcustomerprofitcenter::insert($profit);
				}
			}


			return fractal()
	            ->item($gnmstcustomer)
	            ->transformWith(new GnmstcustomerTransformer)
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

			$gnmstcustomerbank->CompanyCode = $request->get('CompanyCode', $gnmstcustomerbank->CompanyCode);
			$gnmstcustomerbank->CustomerCode = $request->get('CustomerCode', $gnmstcustomerbank->CustomerCode);
			$gnmstcustomerbank->BankCode = $request->get('BankCode', $gnmstcustomerbank->BankCode);
			$gnmstcustomerbank->BankName = $request->get('BankName', $gnmstcustomerbank->BankName);
			$gnmstcustomerbank->AccountName = $request->get('AccountName', $gnmstcustomerbank->AccountName);
			$gnmstcustomerbank->AccountBank = $request->get('AccountBank', $gnmstcustomerbank->AccountBank);
			$gnmstcustomerbank->CreatedBy = $request->get('CreatedBy', $gnmstcustomerbank->CreatedBy);
			$gnmstcustomerbank->CreatedDate = $request->get('CreatedDate', $gnmstcustomerbank->CreatedDate);
			$gnmstcustomerbank->LastUpdateBy = $request->get('LastUpdateBy', $gnmstcustomerbank->LastUpdateBy);
			$gnmstcustomerbank->LastUpdateDate = $request->get('LastUpdateDate', $gnmstcustomerbank->LastUpdateDate);
			$gnmstcustomerbank->isLocked = $request->get('isLocked', $gnmstcustomerbank->isLocked);
			$gnmstcustomerbank->LockingBy = $request->get('LockingBy', $gnmstcustomerbank->LockingBy);
			$gnmstcustomerbank->LockingDate = $request->get('LockingDate', $gnmstcustomerbank->LockingDate);
			$gnmstcustomerbank->save();
		
			$gnmstcustomerprofitcenter->CompanyCode = $request->get('CompanyCode', $gnmstcustomerprofitcenter->CompanyCode);
			$gnmstcustomerprofitcenter->BranchCode = $request->get('BranchCode', $gnmstcustomerprofitcenter->BranchCode);
			$gnmstcustomerprofitcenter->CustomerCode = $request->get('CustomerCode', $gnmstcustomerprofitcenter->CustomerCode);
			$gnmstcustomerprofitcenter->ProfitCenterCode = $request->get('ProfitCenterCode', $gnmstcustomerprofitcenter->ProfitCenterCode);
			$gnmstcustomerprofitcenter->CreditLimit = $request->get('CreditLimit', $gnmstcustomerprofitcenter->CreditLimit);
			$gnmstcustomerprofitcenter->PaymentCode = $request->get('PaymentCode', $gnmstcustomerprofitcenter->PaymentCode);
			$gnmstcustomerprofitcenter->CustomerClass = $request->get('CustomerClass', $gnmstcustomerprofitcenter->CustomerClass);
			$gnmstcustomerprofitcenter->TaxCode = $request->get('TaxCode', $gnmstcustomerprofitcenter->TaxCode);
			$gnmstcustomerprofitcenter->TaxTransCode = $request->get('TaxTransCode', $gnmstcustomerprofitcenter->TaxTransCode);
			$gnmstcustomerprofitcenter->DiscPct = $request->get('DiscPct', $gnmstcustomerprofitcenter->DiscPct);
			$gnmstcustomerprofitcenter->LaborDiscPct = $request->get('LaborDiscPct', $gnmstcustomerprofitcenter->LaborDiscPct);
			$gnmstcustomerprofitcenter->PartDiscPct = $request->get('PartDiscPct', $gnmstcustomerprofitcenter->PartDiscPct);
			$gnmstcustomerprofitcenter->MaterialDiscPct = $request->get('MaterialDiscPct', $gnmstcustomerprofitcenter->MaterialDiscPct);
			$gnmstcustomerprofitcenter->TOPCode = $request->get('TOPCode', $gnmstcustomerprofitcenter->TOPCode);
			$gnmstcustomerprofitcenter->CustomerGrade = $request->get('CustomerGrade', $gnmstcustomerprofitcenter->CustomerGrade);
			$gnmstcustomerprofitcenter->ContactPerson = $request->get('ContactPerson', $gnmstcustomerprofitcenter->ContactPerson);
			$gnmstcustomerprofitcenter->CollectorCode = $request->get('CollectorCode', $gnmstcustomerprofitcenter->CollectorCode);
			$gnmstcustomerprofitcenter->GroupPriceCode = $request->get('GroupPriceCode', $gnmstcustomerprofitcenter->GroupPriceCode);
			$gnmstcustomerprofitcenter->isOverDueAllowed = $request->get('isOverDueAllowed', $gnmstcustomerprofitcenter->isOverDueAllowed);
			$gnmstcustomerprofitcenter->SalesCode = $request->get('SalesCode', $gnmstcustomerprofitcenter->SalesCode);
			$gnmstcustomerprofitcenter->SalesType = $request->get('SalesType', $gnmstcustomerprofitcenter->SalesType);
			$gnmstcustomerprofitcenter->Salesman = $request->get('Salesman', $gnmstcustomerprofitcenter->Salesman);
			$gnmstcustomerprofitcenter->isBlackList = $request->get('isBlackList', $gnmstcustomerprofitcenter->isBlackList);
			$gnmstcustomerprofitcenter->CreatedBy = $request->get('CreatedBy', $gnmstcustomerprofitcenter->CreatedBy);
			$gnmstcustomerprofitcenter->CreatedDate = $request->get('CreatedDate', $gnmstcustomerprofitcenter->CreatedDate);
			$gnmstcustomerprofitcenter->LastUpdateBy = $request->get('LastUpdateBy', $gnmstcustomerprofitcenter->LastUpdateBy);
			$gnmstcustomerprofitcenter->LastUpdateDate = $request->get('LastUpdateDate', $gnmstcustomerprofitcenter->LastUpdateDate);
			$gnmstcustomerprofitcenter->isLocked = $request->get('isLocked', $gnmstcustomerprofitcenter->isLocked);
			$gnmstcustomerprofitcenter->LockingBy = $request->get('LockingBy', $gnmstcustomerprofitcenter->LockingBy);
			$gnmstcustomerprofitcenter->LockingDate = $request->get('LockingDate', $gnmstcustomerprofitcenter->LockingDate);
			$gnmstcustomerbank->save();

			return fractal()
            ->item($gnmstcustomer)
            ->transformWith(new SupplierTransformer)
            ->toArray();
		}
	}
