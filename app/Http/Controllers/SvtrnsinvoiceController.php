<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Gnmstdocument;
use App\Svtrnservice;
use App\Svtrnsrvitem;
use App\Svtrnsrvtask;
use App\Svtrninvoice;

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
        $duedateOdd = date("Y-m-d", strtotime("+1 month", strtotime($invdate)));
        $duedate = $duedateOdd.' '.$invdateEx[1];

    	$service = Svtrnservice::where('CompanyCode', $request->CompanyCode)
    						->where('BranchCode', $branchcode)
    						->where('ProductType', $request->ProductType)
    						->where('InvDocNo', $request->InvDocNo)
    						->first();
    	// chasis
    	$chassisCode = substr($request->VIN, 0, 12);
    	$chassisNo = substr($request->VIN, 12, 5);

    	// engine code 
    	$engineCode = substr($request->Engine, 0, 7);
    	$engineNo = substr($request->Engine, 7, 5);

    	$desc = "Created By RDMS : ". $request->InvDocNo;

    	// discount
    	$disc = $request->AmountDiscount / ($request->RetailPrice * $request->SupplyQty);

    	if ($service == null) {
    		$spk = $this->noUrut('SPK', $branchcode, $request->CompanyCode);
    		$inc = $this->noUrut('INC', $branchcode, $request->CompanyCode);
    		$sss = $this->noUrut('SSS', $branchcode, $request->CompanyCode);
    		$fps = $this->noUrut('FPS', $branchcode, $request->CompanyCode);

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
    			Svtrninvoice::firstOrCreate([
    				'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'ProductType' => $request->ProductType,
					'InvoiceNo' => $inc,
					'InvoiceDate' => $invdate,
					'InvoiceStatus' => $request->InvoiceStatus,
					'FPJNo' => $fps,
					'FPJDate' => $invdate,
					'JobOrderNo' => $spk,
					'JobOrderDate' => $invdate,
					'JobType' => $request->JobType,
					'ServiceRequestDesc' => $desc,
					'ChassisCode' => $chassisCode,
					'ChassisNo' => $chassisNo,
					'EngineCode' => $engineCode,
					'EngineNo' => $engineNo,
					'PoliceRegNo' => $request->PoliceRegNo,
					'BasicModel' => $request->BasicModel,
					'CustomerCode' => $request->CustomerCode,
					'CustomerCodeBill' => $request->CustomerCodeBill,
					'Odometer' => $request->Odometer,
					'IsPKP' => $request->IsPKP,
					'TOPCode' => $request->TOPCode,
					'TOPDays' => $request->TOPDays,
					'DueDate' => $duedate,
					'SignedDate' => $invdate,
					'LaborDiscPct' => 0,
					'PartsDiscPct' => 0,
					'MaterialDiscPct' => 0,
					'PphPct' => $request->PPHPct,
					'PpnPct' => $request->PPNPct,
					'LaborGrossAmt' => 0,
					'PartsGrossAmt' => 0,
					'MaterialGrossAmt' => 0,
					'LaborDiscAmt' => 0,
					'PartsDiscAmt' => 0,
					'MaterialDiscAmt' => 0,
					'LaborDppAmt' => 0,
					'PartsDppAmt' => 0,
					'MaterialDppAmt' => 0,
					'TotalDppAmt' => 0,
					'TotalPphAmt' => 0,
					'TotalPpnAmt' => 0,
					'TotalSrvAmt' => 0,
					'Remarks' => $request->Remarks,
					'PrintSeq' => $request->PrintSeq,
					'PostingFlag' => $request->PostingFlag,
					'PostingDate' => NULL,
					'IsLocked' => False,
					'LockingBy' => NULL,
					'LockingDate' => NULL,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastupdateBy' => $request->LastUpdateBy,
					'LastupdateDate' => Carbon::now(),
					'InvDocNo' => $request->InvDocNo,
    			]);

    			if ($request->Remarks == 'Part' Or $request->Remarks == 'Oil') {

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
						'AmountDiscount' => $request->AmountDiscount,
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
						'AmountDiscount' => $request->AmountDiscount,
    				]);
    			}

    			$this->updateHeader($request->InvDocNo, $servno, $request->Remarks);

    		}

    		return response()->json([
                'data' => 0
            ], 200);
    	} else {
    		if ($request->Remarks == 'Part' Or $request->Remarks == 'Oil') {
    			$partseq = Svtrnsrvitem::where('CompanyCode', $service->CompanyCode)
    										->where('BranchCode', $service->BranchCode)
    										->where('ProductType', $service->ProductType)
    										->where('ServiceNo', $service->ServiceNo)
    										->where('PartNo', $request->PartNo)
    										->orderBy('PartSeq', 'DESC')
    										->first();
    			if ($partseq == null) {
    				$partseq_no = 1;
    				$sss = $this->noUrut('SSS', $branchcode, $request->CompanyCode);
    			} else {
    				$partseq_no = $partseq->PartSeq + 1;
    				$sss = $partseq->SupplySlipNo;
    			}

    			$svtrnsrvitem = Svtrnsrvitem::where('CompanyCode', $service->CompanyCode)
    										->where('BranchCode', $service->BranchCode)
    										->where('ProductType', $service->ProductType)
    										->where('ServiceNo', $service->ServiceNo)
    										->where('PartNo', $request->PartNo)
    										->where('PartSeq', $partseq_no)
    										->first();
    			if ($svtrnsrvitem == null) {
    				Svtrnsrvitem::firstOrCreate([
						'CompanyCode' => $request->CompanyCode,
						'BranchCode' => $branchcode,
						'ProductType' => $request->ProductType,
						'ServiceNo' => $service->ServiceNo,
						'PartNo' => $request->PartNo,
						'PartSeq' => $partseq_no,
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
						'AmountDiscount' => $request->AmountDiscount,
					]);
    			} 
					
			} else {
				$svtrntask = Svtrnsrvtask::where('CompanyCode', $service->CompanyCode)
										->where('BranchCode', $service->BranchCode)
										->where('ProductType', $service->ProductType)
    									->where('ServiceNo', $service->ServiceNo)
    									->where('OperationNo', $request->OperationNo)
    									->first();
    			if ($svtrntask == null) {
    				Svtrnsrvtask::firstOrCreate([
						'CompanyCode' => $request->CompanyCode,
						'BranchCode' => $branchcode,
						'ProductType' => $request->ProductType,
						'ServiceNo' => $service->ServiceNo,
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
						'AmountDiscount' => $request->AmountDiscount,
					]);
    			}

					
			}

			$this->updateHeader($request->InvDocNo, $service->ServiceNo, $request->Remarks);

			return response()->json([
                'data' => 1
            ], 200);
    	}
    }

    public function updateHeader($invno, $serno, $remaks)
    {
    	$labordiscpct = 0;
    	$partdispct = 0;
    	$laborgrossamt = 0;
    	$partsgrossamt = 0;
    	$labordiscamt = 0;
    	$partsdiscamt = 0;
    	$materialdiscpct = 0;
    	$labordppamt = 0;
    	$partsdppamt = 0;
    	$totaldppamount = 0;
    	$totalppnamount = 0;
    	$totalsrvamount = 0;


    	if ($remaks == 'Part' Or $remaks == 'Oil') {
	    	$detail = Svtrnsrvitem::where('ServiceNo', $serno)->get();
	    	foreach ($detail as $row) {
		    	$partdispct = $partdispct + $row->DiscPct;
		    	$sum = $row->RetailPrice * $row->SupplyQty;

		    	$partsgrossamt = $partsgrossamt + $sum;

		    	

		    	$partsdiscamt = $partsdiscamt + $row->AmountDiscount;

		    	$partsdppamt1 = $sum - $row->AmountDiscount;
		    	$partsdppamt = $partsdppamt + $partsdppamt1;
	    	}

	    	

	    	Svtrnservice::where('InvDocNo', $invno)
	    				->update([
							'PartDiscPct' => $partdispct,
							'PartsGrossAmt' => $partsgrossamt,
							'PartsDiscAmt' => $partsdiscamt,
							'PartsDppAmt' => $partsdppamt,
	    				]);

	    	Svtrninvoice::where('InvDocNo', $invno)
	    				->update([
							'PartsDiscPct' => $partdispct,
							'PartsGrossAmt' => $partsgrossamt,
							'PartsDiscAmt' => $partsdiscamt,
							'PartsDppAmt' => $partsdppamt,
	    				]);
	    } else {
	    	$detail = Svtrnsrvtask::where('ServiceNo', $serno)->get();
	    	foreach ($detail as $row) {
	    		$labordiscpct = $labordiscpct + $row->DiscPct;
	    		$sum = $row->OperationHour * $row->OperationCost;

	    		$laborgrossamt = $laborgrossamt + $sum;

	    		$labordiscamt = $labordiscamt + $row->AmountDiscount;

	    		$labordppamt1 = $sum - $row->AmountDiscount;
	    		$labordppamt = $labordppamt + $labordppamt1;
	    	}

	    	Svtrnservice::where('InvDocNo', $invno)
	    				->update([
	    					'LaborDiscPct' => $labordiscpct,
							'LaborGrossAmt' => $laborgrossamt,
							'LaborDiscAmt' => $labordiscamt,
							'LaborDppAmt' => $labordppamt,
	    				]);

	    	Svtrninvoice::where('InvDocNo', $invno)
	    				->update([
	    					'LaborDiscPct' => $labordiscpct,
							'LaborGrossAmt' => $laborgrossamt,
							'LaborDiscAmt' => $labordiscamt,
							'LaborDppAmt' => $labordppamt,
	    				]);
	    }

	    

    	$service = Svtrnservice::where('InvDocNo', $invno)->first();
    	if ($service) {
    		// $totaldppamount = $labordppamt + $partsdppamt;
    		$totaldppamount = $service->LaborDppAmt + $service->PartsDppAmt;
	    	$totalppnamount = 0.1 * $totaldppamount;
	    	$totalsrvamount = $totaldppamount + $totalppnamount;

    		Svtrnservice::where('InvDocNo', $invno)
				->update([
					'TotalDPPAmount' => $totaldppamount,
					'TotalPpnAmount' => $totalppnamount,
					'TotalSrvAmount' => $totalsrvamount,
				]);

			Svtrninvoice::where('InvDocNo', $invno)
				->update([
					'TotalDppAmt' => $totaldppamount,
					'TotalPpnAmt' => $totalppnamount,
					'TotalSrvAmt' => $totalsrvamount,
				]);
    	}

		    

		    

    	// docno arbegin
		// $invnoEx = explode("/", $invodd);
		// $docFirst = substr($invnoEx[0], 1, 3);
		// $docNoArbegin = $docFirst .'/'. $invnoEx[3].'/'.$invnoEx[2].$invnoEx[1].$invnoEx[4];

		// Arbeginbalancehdr::where('DocNo', $docNoArbegin)
		// 			->update([
		// 				'Amount' => $totfinal,
		// 			]);

		// Arbeginbalancedtl::where('DocNo', $docNoArbegin)
		// 			->update([
		// 				'Amount' => $totfinal
		// 			]);

    }
}
