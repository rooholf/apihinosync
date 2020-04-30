<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Gnmstdocument;
use App\Svtrnservice;
use App\Svtrnsrvitem;
use App\Svtrnsrvtask;

use Carbon\Carbon;

class SvtrnsinvoiceController extends Controller
{

	public function noUrut($type, $branchcode, $companycode)
    {
        $gnmstdocument = Gnmstdocument::where('DocumentType', $type)
                                    ->where('BranchCode', $branchcode)
                                    ->where('CompanyCode', $companycode)
                                    ->first();

        $no = $gnmstdocument->DocumentSequence + 1;
        $thn = substr($gnmstdocument->DocumentYear, 2, 2);
        $nourut = sprintf("%06s", $no);

        $newnumb = $type.'/'.$thn.'/'.$nourut;

        Gnmstdocument::where('DocumentType', $type)
            ->where('BranchCode', $branchcode)
            ->where('CompanyCode', $companycode)
            ->update(['DocumentSequence' => $no]);

        return $newnumb;
    }

    public function add(Request $request)
    {
    	$this->validate($request, [
            'InvDocNo' => 'required',
        ]);

        if ($request->Dealer == 'After Sales & Services - JIM Muara Bungo') {
            $branchcode = '002';
        } else {
            $branchcode = '000';
        }

        // date
        $invdateEx = explode(" ", $request->InvDate);

        $invdate = $invdateEx[0].' '.$invdateEx[1];

    	$service = Svtrnservice::where('CompanyCode', $request->CompanyCode)
    						->where('BranchCode', $branchcode)
    						->where('ProductType', $request->ProductType)
    						->where('InvDocNo', $request->InvDocNo)
    						->first();
    	// chasis
    	$chassisCode = substr($request->VIN, 0, 12);
    	$chassisNo = substr($request->VIN, 12, 5);

    	// engine code 
    	$engineCode = substr($request->EngineCode, 0, 7);
    	$engineNo = substr($request->EngineCode, 7, 5);

    	$desc = "Created By RDMS : ". $request->InvDocNo;

    	// discount
    	$disc = ($request->AmountDiscount / $request->RetailPrice) * $request->SupplyQty;

    	if ($service == null) {
    		$spk = $this->noUrut('SPK', $branchcode, $request->CompanyCode);
    		$inc = $this->noUrut('INC', $branchcode, $request->CompanyCode);
    		$sss = $this->noUrut('SSS', $branchcode, $request->CompanyCode);

    		$no = Svtrnservice::orderBy('ServiceNo', 'DESC')->first();
    		$servno = $no->ServiceNo + 1;

    		$svtrnservice = Svtrnservice::firstOrCreate([
    			'CompanyCode' => $request->CompanyCode,
				'BranchCode' => $branchcode,
				'ProductType' => $request->ProductType,
				'ServiceNo' => $servno,
				'ServiceType' => $request->ServiceType,
				'ServiceStatus' => $request->ServiceStatus,
				'JobOrderNo' => $spk,
				'JobOrderDate' => $invdate,
				'EstimationNo' => '',
				'EstimationDate' => $request->EstimationDate,
				'BookingNo' => '',
				'BookingDate' => $request->BookingDate,
				'InvoiceNo' => $inc,
				'ForemanID' => $request->ForemanID,
				'MechanicID' => $request->MechanicID,
				'CustomerCode' => $request->CustomerCode,
				'CustomerCodeBill' => $request->CustomerCodeBill,
				'PoliceRegNo' => $request->PoliceRegNo,
				'ServiceBookNo' => '',
				'BasicModel' => $request->BasicModel,
				'TransmissionType' => $request->TransmissionType,
				'VIN' => $request->VIN,
				'ChassisCode' => $chassisCode,
				'ChassisNo' => $chassisNo,
				'EngineCode' => $engineCode,
				'EngineNo' => $engineNo,
				'ColorCode' => $request->ColorCode,
				'Odometer' => $request->Odometer,
				'JobType' => $request->JobType,
				'ServiceRequestDesc' => $desc,
				'ConfirmChangingPart' => $request->ConfirmChangingPart,
				'EstimateFinishDate' => $invdate,
				'EstimateFinishDateSys' => $invdate,
				'LaborDiscPct' => 0,
				'PartDiscPct' => 0,
				'MaterialDiscAmt' => 0,
				'InsurancePayFlag' => $request->InsurancePayFlag,
				'InsuranceOwnRisk' => '0',
				'InsuranceNo' => '',
				'InsuranceJobOrderNo' => '',
				'PPNPct' => $request->PPNPct,
				'PPHPct' => $request->PPHPct,
				'LaborGrossAmt' => 0,
				'PartsGrossAmt' => 0,
				'MaterialGrossAmt' => 0,
				'LaborDiscAmt' => 0,
				'PartsDiscAmt' => 0,
				'MaterialDiscPct' => 0,
				'LaborDppAmt' => 0,
				'PartsDppAmt' => 0,
				'MaterialDppAmt' => 0,
				'TotalDPPAmount' => 0,
				'TotalPphAmount' => 0,
				'TotalPpnAmount' => 0,
				'TotalSrvAmount' => 0,
				'PrintSeq' => $request->PrintSeq,
				'IsLocked' => $request->IsLocked,
				'LockingBy' => $request->LockingBy,
				'LockingDate' => Carbon::now(),
				'CreatedBy' => $request->CreatedBy,
				'CreatedDate' => Carbon::now(),
				'LastUpdateBy' => $request->LastUpdateBy,
				'LastUpdateDate' => Carbon::now(),
				'IsSparepartClaim' => $request->IsSparepartClaim,
				'JobOrderClosed' => $request->JobOrderClosed,
				'InvDocNo' => $request->InvDocNo,
    		]);

    		if ($svtrnservice) {
    			if ($request->Remarks == 'Part') {
    				Svtrnsrvitem::firstOrCreate([
    					'CompanyCode' => $request->CompanyCode,
						'BranchCode' => $branchcode,
						'ProductType' => $request->ProductType,
						'ServiceNo' => $servno,
						'PartNo' => $request->PartNo,
						'PartSeq' => 1,
						'DemandQty' => $request->DemandQty,
						'SupplyQty' => $request->SupplyQty,
						'ReturnQty' => $request->ReturnQty,
						'CostPrice' => $request->CostPrice,
						'RetailPrice' => $request->RetailPrice,
						'TypeOfGoods' => $request->TypeOfGoods,
						'BillType' => $request->BillType,
						'SupplySlipNo' => $sss,
						'SupplySlipDate' => $invdate,
						'SSReturnNo' => NULL,
						'SSReturnDate' => $invdate,
						'CreatedBy' => $request->CreatedBy,
						'CreatedDate' => Carbon::now(),
						'LastupdateBy' => $request->LastupdateBy,
						'LastupdateDate' => Carbon::now(),
						'DiscPct' => $disc,
						'MechanicID' => $request->MechanicID,
    				]);
    			} else {
    				Svtrnsrvtask::firstOrCreate([
    					'CompanyCode' => $request->CompanyCode,
						'BranchCode' => $branchcode,
						'ProductType' => $request->ProductType,
						'ServiceNo' => $servno,
						'OperationNo' => $request->OperationNo,
						'OperationHour' => $request->OperationHour,
						'OperationCost' => $request->OperationCost,
						'IsSubCon' => $request->IsSubCon,
						'SubConPrice' => $request->SubConPrice,
						'PONo' => '',
						'ClaimHour' => $request->ClaimHour,
						'TypeOfGoods' => $request->TypeOfGoods,
						'BillType' => $request->BillType,
						'SharingTask' => $request->SharingTask,
						'TaskStatus' => $request->TaskStatus,
						'StartService' => $invdate,
						'FinishService' => $invdate,
						'CreatedBy' => $request->CreatedBy,
						'CreatedDate' => Carbon::now(),
						'LastupdateBy' => $request->LastupdateBy,
						'LastupdateDate' => Carbon::now(),
						'DiscPct' => $disc,
    				]);
    			}

    		}
    	} else {
    		if ($request->Remarks == 'Part') {
    			$svtrnsrvitem = Svtrnsrvitem::where('CompanyCode', $service->CompanyCode)
    										->where('BranchCode', $service->BranchCode)
    										->where('ProductType', $service->ProductType)
    										->where('ServiceNo', $service->ServiceNo)
    										->where('PartNo', $request->PartNo)
    										->orderBy('PartSeq', 'DESC')
    										->first();
    			if ($svtrnsrvitem == null) {
    				Svtrnsrvitem::firstOrCreate([
						'CompanyCode' => $request->CompanyCode,
						'BranchCode' => $branchcode,
						'ProductType' => $request->ProductType,
						'ServiceNo' => $servno,
						'PartNo' => $request->PartNo,
						'PartSeq' => 1,
						'DemandQty' => $request->DemandQty,
						'SupplyQty' => $request->SupplyQty,
						'ReturnQty' => $request->ReturnQty,
						'CostPrice' => $request->CostPrice,
						'RetailPrice' => $request->RetailPrice,
						'TypeOfGoods' => $request->TypeOfGoods,
						'BillType' => $request->BillType,
						'SupplySlipNo' => $sss,
						'SupplySlipDate' => $invdate,
						'SSReturnNo' => NULL,
						'SSReturnDate' => $invdate,
						'CreatedBy' => $request->CreatedBy,
						'CreatedDate' => Carbon::now(),
						'LastupdateBy' => $request->LastupdateBy,
						'LastupdateDate' => Carbon::now(),
						'DiscPct' => $disc,
						'MechanicID' => $request->MechanicID,
					]);
    			} else {
    				$partseq = $svtrnsrvitem->PartSeq + 1;

    				Svtrnsrvitem::firstOrCreate([
						'CompanyCode' => $request->CompanyCode,
						'BranchCode' => $branchcode,
						'ProductType' => $request->ProductType,
						'ServiceNo' => $servno,
						'PartNo' => $request->PartNo,
						'PartSeq' => $partseq,
						'DemandQty' => $request->DemandQty,
						'SupplyQty' => $request->SupplyQty,
						'ReturnQty' => $request->ReturnQty,
						'CostPrice' => $request->CostPrice,
						'RetailPrice' => $request->RetailPrice,
						'TypeOfGoods' => $request->TypeOfGoods,
						'BillType' => $request->BillType,
						'SupplySlipNo' => $sss,
						'SupplySlipDate' => $invdate,
						'SSReturnNo' => NULL,
						'SSReturnDate' => $invdate,
						'CreatedBy' => $request->CreatedBy,
						'CreatedDate' => Carbon::now(),
						'LastupdateBy' => $request->LastupdateBy,
						'LastupdateDate' => Carbon::now(),
						'DiscPct' => $disc,
						'MechanicID' => $request->MechanicID,
					]);
    			}
					
			} else {
				Svtrnsrvtask::firstOrCreate([
					'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'ProductType' => $request->ProductType,
					'ServiceNo' => $servno,
					'OperationNo' => $request->OperationNo,
					'OperationHour' => $request->OperationHour,
					'OperationCost' => $request->OperationCost,
					'IsSubCon' => $request->IsSubCon,
					'SubConPrice' => $request->SubConPrice,
					'PONo' => '',
					'ClaimHour' => $request->ClaimHour,
					'TypeOfGoods' => $request->TypeOfGoods,
					'BillType' => $request->BillType,
					'SharingTask' => $request->SharingTask,
					'TaskStatus' => $request->TaskStatus,
					'StartService' => $invdate,
					'FinishService' => $invdate,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastupdateBy' => $request->LastupdateBy,
					'LastupdateDate' => Carbon::now(),
					'DiscPct' => $disc,
				]);
			}
    	}
    }
}
