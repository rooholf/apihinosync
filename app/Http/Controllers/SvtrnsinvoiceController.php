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
use App\Svtrninvitem;
use App\Svtrninvitemdtl;
use App\Svtrninvtask;
use App\Svtrnfakturpajak;
use App\Arbeginbalancehdr;
use App\Arbeginbalancedtl;

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
        $nourut = sprintf('%06s', $no);

        $newnumb = $type . '/' . $thn . '/' . $nourut;

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

        if ($request->Dealer == 'Service - JIM Muara Bungo') {
            $branchcode = '1002';
            $accountNo = '004.002.000.00000.200000.000.000';
        } elseif ($request->Dealer == 'Service - Onsite JIM SBNT') {
            $branchcode = '1003';
            $accountNo = '004.003.000.00000.200000.000.000';
        } elseif ($request->Dealer == 'Service - Onsite JIM PLKT') {
            $branchcode = '1004';
            $accountNo = '004.004.000.00000.200000.000.000';
        } elseif ($request->Dealer == 'Service - Onsite JIM SMKE') {
            $branchcode = '1005';
            $accountNo = '004.005.000.00000.200000.000.000';
        } else {
            $branchcode = '1000';
            $accountNo = '004.000.000.00000.200000.000.000';
        }

        // date
        $invdateEx = explode(' ', $request->InvDate);

        $invdate = $invdateEx[0] . ' ' . $invdateEx[1];
        $duedateOdd = date('Y-m-d', strtotime('+1 month', strtotime($invdate)));
        $duedate = $duedateOdd . ' ' . $invdateEx[1];

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

        $desc = 'Created By RDMS : ' . $request->InvDocNo;

        $amt = (float) $request->SupplyQty * (int) $request->RetailPrice;

        // discount
        $disc = ((int) $request->AmountDiscount / (int) $amt) * 100;

        // docno arbegin
        $invnoEx = explode('/', $request->InvDocNo);
        $docFirst = substr($invnoEx[0], 1, 3);
        $docNoArbegin =
            $docFirst .
            '/' .
            $invnoEx[3] .
            '/' .
            $invnoEx[2] .
            $invnoEx[1] .
            $invnoEx[4];

        // Nembak AR
        // Dari sini
        // if ($service) {
        //     Arbeginbalancehdr::firstOrCreate([
        //         'CompanyCode' => $request->CompanyCode,
        //         'BranchCode' => $branchcode,
        //         'DocNo' => $docNoArbegin,
        //         'ProfitCenterCode' => '200',
        //         'DocDate' => $invdate,
        //         'CustomerCode' => $request->CustomerCode,
        //         'AccountNo' => $accountNo,
        //         'DueDate' => $duedate,
        //         'TOPCode' => $request->TOPCode,
        //         'Amount' => 0,
        //         'SalesCode' => '',
        //         'LeasingCode' => '',
        //         'Status' => 0,
        //         'CreatedBy' => $request->CreatedBy,
        //         'CreatedDate' => Carbon::now(),
        //         'PrintSeq' => '1',
        //     ]);

        //     Arbeginbalancedtl::firstOrCreate([
        //         'CompanyCode' => $request->CompanyCode,
        //         'BranchCode' => $branchcode,
        //         'DocNo' => $docNoArbegin,
        //         'SeqNo' => '1',
        //         'AccountNo' => $accountNo,
        //         'Description' => '',
        //         'Amount' => 0,
        //         'Status' => '',
        //         'CreatedBy' => $request->CreatedBy,
        //         'CreatedDate' => Carbon::now(),
        //     ]);
        // }
        // nembak header dan detail
        if ($service) {
            $this->updateDetail(
                $branchcode,
                $service->ServiceNo,
                $service->ProductType,
                $service->OperationNo,
                $request->PartNo,
                $service->InvoiceNo,
                $service->SupplySlipNo,
                $request->AmountDiscount,
                $request->RetailPrice,
                $request->SupplyQty,
                $request->OperationHour,
                $request->OperationCost
            );

            $this->updateHeader(
                $request->InvDocNo,
                $service->ServiceNo,
                $request->Remarks,
                $request->Amount
            );
        }

        // if ($service == null) {
        //     $spk = $this->noUrut('SPK', $branchcode, $request->CompanyCode);
        //     $inc = $this->noUrut('INC', $branchcode, $request->CompanyCode);
        //     $sss = $this->noUrut('SSS', $branchcode, $request->CompanyCode);
        //     $fps = $this->noUrut('FPS', $branchcode, $request->CompanyCode);

        //     $no = Svtrnservice::orderBy('ServiceNo', 'DESC')->first();
        //     $servno = $no->ServiceNo + 1;

        //     $svtrnservice = Svtrnservice::firstOrCreate(
        //         [
        //             'CompanyCode' => $request->CompanyCode,
        //             'BranchCode' => $branchcode,
        //             'ProductType' => $request->ProductType,
        //             'ServiceNo' => $servno,
        //             'ServiceType' => $request->ServiceType,
        //         ],
        //         [
        //             'CompanyCode' => $request->CompanyCode,
        //             'BranchCode' => $branchcode,
        //             'ProductType' => $request->ProductType,
        //             'ServiceNo' => $servno,
        //             'ServiceType' => $request->ServiceType,
        //             'ServiceStatus' => $request->ServiceStatus,
        //             'JobOrderNo' => $spk,
        //             'JobOrderDate' => $invdate,
        //             'EstimationNo' => '',
        //             'EstimationDate' => $request->EstimationDate,
        //             'BookingNo' => '',
        //             'BookingDate' => $request->BookingDate,
        //             'InvoiceNo' => $inc,
        //             'ForemanID' => $request->ForemanID,
        //             'MechanicID' => $request->MechanicID,
        //             'CustomerCode' => $request->CustomerCode,
        //             'CustomerCodeBill' => $request->CustomerCodeBill,
        //             'PoliceRegNo' => $request->PoliceRegNo,
        //             'ServiceBookNo' => '',
        //             'BasicModel' => $request->BasicModel,
        //             'TransmissionType' => $request->TransmissionType,
        //             'VIN' => $request->VIN,
        //             'ChassisCode' => $chassisCode,
        //             'ChassisNo' => $chassisNo,
        //             'EngineCode' => $engineCode,
        //             'EngineNo' => $engineNo,
        //             'ColorCode' => $request->ColorCode,
        //             'Odometer' => $request->Odometer,
        //             'JobType' => $request->JobType,
        //             'ServiceRequestDesc' => $desc,
        //             'ConfirmChangingPart' => $request->ConfirmChangingPart,
        //             'EstimateFinishDate' => $invdate,
        //             'EstimateFinishDateSys' => $invdate,
        //             'LaborDiscPct' => 0,
        //             'PartDiscPct' => 0,
        //             'MaterialDiscAmt' => 0,
        //             'InsurancePayFlag' => $request->InsurancePayFlag,
        //             'InsuranceOwnRisk' => '0',
        //             'InsuranceNo' => '',
        //             'InsuranceJobOrderNo' => '',
        //             'PPNPct' => $request->PPNPct,
        //             'PPHPct' => $request->PPHPct,
        //             'LaborGrossAmt' => 0,
        //             'PartsGrossAmt' => 0,
        //             'MaterialGrossAmt' => 0,
        //             'LaborDiscAmt' => 0,
        //             'PartsDiscAmt' => 0,
        //             'MaterialDiscPct' => 0,
        //             'LaborDppAmt' => 0,
        //             'PartsDppAmt' => 0,
        //             'MaterialDppAmt' => 0,
        //             'TotalDPPAmount' => 0,
        //             'TotalPphAmount' => 0,
        //             'TotalPpnAmount' => 0,
        //             'TotalSrvAmount' => 0,
        //             'PrintSeq' => $request->PrintSeq,
        //             'IsLocked' => $request->IsLocked,
        //             'LockingBy' => $request->LockingBy,
        //             'LockingDate' => Carbon::now(),
        //             'CreatedBy' => $request->CreatedBy,
        //             'CreatedDate' => Carbon::now(),
        //             'LastUpdateBy' => $request->LastUpdateBy,
        //             'LastUpdateDate' => Carbon::now(),
        //             'IsSparepartClaim' => $request->IsSparepartClaim,
        //             'JobOrderClosed' => $request->JobOrderClosed,
        //             'InvDocNo' => $request->InvDocNo,
        //         ]
        //     );

        //     if ($svtrnservice) {
        //         Svtrninvoice::create([
        //             'CompanyCode' => $request->CompanyCode,
        //             'BranchCode' => $branchcode,
        //             'ProductType' => $request->ProductType,
        //             'InvoiceNo' => $inc,
        //             'InvoiceDate' => $invdate,
        //             'InvoiceStatus' => $request->InvoiceStatus,
        //             'FPJNo' => $fps,
        //             'FPJDate' => $invdate,
        //             'JobOrderNo' => $spk,
        //             'JobOrderDate' => $invdate,
        //             'JobType' => $request->JobType,
        //             'ServiceRequestDesc' => $desc,
        //             'ChassisCode' => $chassisCode,
        //             'ChassisNo' => $chassisNo,
        //             'EngineCode' => $engineCode,
        //             'EngineNo' => $engineNo,
        //             'PoliceRegNo' => $request->PoliceRegNo,
        //             'BasicModel' => $request->BasicModel,
        //             'CustomerCode' => $request->CustomerCode,
        //             'CustomerCodeBill' => $request->CustomerCodeBill,
        //             'Odometer' => $request->Odometer,
        //             'IsPKP' => $request->IsPKP,
        //             'TOPCode' => $request->TOPCode,
        //             'TOPDays' => $request->TOPDays,
        //             'DueDate' => $duedate,
        //             'SignedDate' => $invdate,
        //             'LaborDiscPct' => 0,
        //             'PartsDiscPct' => 0,
        //             'MaterialDiscPct' => 0,
        //             'PphPct' => $request->PPHPct,
        //             'PpnPct' => $request->PPNPct,
        //             'LaborGrossAmt' => 0,
        //             'PartsGrossAmt' => 0,
        //             'MaterialGrossAmt' => 0,
        //             'LaborDiscAmt' => 0,
        //             'PartsDiscAmt' => 0,
        //             'MaterialDiscAmt' => 0,
        //             'LaborDppAmt' => 0,
        //             'PartsDppAmt' => 0,
        //             'MaterialDppAmt' => 0,
        //             'TotalDppAmt' => 0,
        //             'TotalPphAmt' => 0,
        //             'TotalPpnAmt' => 0,
        //             'TotalSrvAmt' => 0,
        //             'Remarks' => $request->Remarks,
        //             'PrintSeq' => $request->PrintSeq,
        //             'PostingFlag' => $request->PostingFlag,
        //             'PostingDate' => null,
        //             'IsLocked' => false,
        //             'LockingBy' => null,
        //             'LockingDate' => null,
        //             'CreatedBy' => $request->CreatedBy,
        //             'CreatedDate' => Carbon::now(),
        //             'LastupdateBy' => $request->LastUpdateBy,
        //             'LastupdateDate' => Carbon::now(),
        //             'InvDocNo' => $request->InvDocNo,
        //         ]);

        //         Svtrnfakturpajak::create([
        //             'CompanyCode' => $request->CompanyCode,
        //             'BranchCode' => $branchcode,
        //             'FPJNo' => $fps,
        //             'FPJDate' => $invdate,
        //             'FPJGovNo' => $request->FPJGovNo,
        //             'FPJCentralNo' => '',
        //             'FPJCentralDate' => $request->FPJCentralDate,
        //             'NoOfInvoice' => $request->NoOfInvoice,
        //             'CustomerCode' => $request->CustomerCode,
        //             'CustomerCodeBill' => $request->CustomerCodeBill,
        //             'CustomerName' => $request->CustomerName,
        //             'Address1' => str_replace(',', ' ', $request->Address1),
        //             'Address2' => str_replace(',', ' ', $request->Address2),
        //             'Address3' => str_replace(',', ' ', $request->Address3),
        //             'Address4' => '',
        //             'PhoneNo' => $request->PhoneNo,
        //             'HPNo' => $request->HPNo,
        //             'IsPKP' => $request->IsPKP,
        //             'SKPNo' => $request->SKPNo,
        //             'SKPDate' => $request->SKPDate,
        //             'NPWPNo' => $request->NPWPNo,
        //             'NPWPDate' => $request->NPWPDate,
        //             'TOPCode' => $request->TOPCode,
        //             'TOPDays' => $request->TOPDays,
        //             'DueDate' => $duedate,
        //             'SignedDate' => $invdate,
        //             'PrintSeq' => $request->PrintSeq,
        //             'GenerateStatus' => $request->GenerateStatus,
        //             'IsLocked' => $request->IsLocked,
        //             'LockingBy' => null,
        //             'LockingDate' => $request->LockingDate,
        //             'CreatedBy' => $request->CreatedBy,
        //             'CreatedDate' => Carbon::now(),
        //             'LastupdateBy' => $request->LastUpdateBy,
        //             'LastupdateDate' => Carbon::now(),
        //         ]);

        //         if (
        //             $request->Remarks == 'Sparepart' or
        //             $request->Remarks == 'Oil' or
        //             $request->Remarks == 'Material'
        //         ) {
        //             Svtrnsrvitem::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'ServiceNo' => $servno,
        //                 'PartNo' => $request->PartNo,
        //                 'PartSeq' => 1,
        //                 'DemandQty' => $request->DemandQty,
        //                 'SupplyQty' => $request->SupplyQty,
        //                 'ReturnQty' => $request->ReturnQty,
        //                 'CostPrice' => $request->CostPrice,
        //                 'RetailPrice' => (int) $request->RetailPrice,
        //                 'TypeOfGoods' => $request->TypeOfGoods,
        //                 'BillType' => $request->BillType,
        //                 'SupplySlipNo' => $sss,
        //                 'SupplySlipDate' => $invdate,
        //                 'SSReturnNo' => null,
        //                 'SSReturnDate' => $invdate,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //                 'LastupdateBy' => $request->LastUpdateBy,
        //                 'LastupdateDate' => Carbon::now(),
        //                 'DiscPct' => round(
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->RetailPrice *
        //                             (float) $request->SupplyQty)) *
        //                         100,
        //                     2
        //                 ),
        //                 'MechanicID' => $request->MechanicID,
        //                 'AmountDiscount' => $request->AmountDiscount,
        //             ]);

        //             Svtrninvitem::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'InvoiceNo' => $inc,
        //                 'PartNo' => $request->PartNo,
        //                 'MovingCode' => $request->MovingCode,
        //                 'ABCClass' => $request->ABCClass,
        //                 'SupplyQty' => $request->SupplyQty,
        //                 'ReturnQty' => $request->ReturnQty,
        //                 'CostPrice' => $request->CostPrice,
        //                 'RetailPrice' => (int) $request->RetailPrice,
        //                 'TypeOfGoods' => $request->TypeOfGoods,
        //                 'DiscPct' =>
        //                     ((int) $request->AmountDiscount /
        //                         ($request->RetailPrice *
        //                             (float) $request->SupplyQty)) *
        //                     100,
        //                 'MechanicID' => $request->MechanicID,
        //                 'CreatedBy' => $request->CreatedBy,
        //             ]);

        //             Svtrninvitemdtl::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'InvoiceNo' => $inc,
        //                 'PartNo' => $request->PartNo,
        //                 'SupplySlipNo' => $sss,
        //                 'SupplyQty' => $request->SupplyQty,
        //                 'CostPrice' => $request->CostPrice,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //             ]);
        //         } else {
        //             Svtrnsrvtask::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'ServiceNo' => $servno,
        //                 'OperationNo' => $request->OperationNo,
        //                 'OperationHour' => $request->OperationHour,
        //                 'OperationCost' => $request->OperationCost,
        //                 'IsSubCon' => $request->IsSubCon,
        //                 'SubConPrice' => $request->SubConPrice,
        //                 'PONo' => '',
        //                 'ClaimHour' => $request->ClaimHour,
        //                 'TypeOfGoods' => $request->TypeOfGoods,
        //                 'BillType' => $request->BillType,
        //                 'SharingTask' => $request->SharingTask,
        //                 'TaskStatus' => $request->TaskStatus,
        //                 'StartService' => $invdate,
        //                 'FinishService' => $invdate,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //                 'LastupdateBy' => $request->LastUpdateBy,
        //                 'LastupdateDate' => Carbon::now(),
        //                 'DiscPct' =>
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->OperationCost *
        //                             (int) $request->OperationHour)) *
        //                     100,
        //                 'AmountDiscount' => $request->AmountDiscount,
        //             ]);

        //             Svtrninvtask::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'InvoiceNo' => $inc,
        //                 'OperationNo' => $request->OperationNo,
        //                 'OperationHour' => $request->OperationHour,
        //                 'ClaimHour' => $request->ClaimHour,
        //                 'OperationCost' => $request->OperationCost,
        //                 'SubConPrice' => $request->SubConPrice,
        //                 'IsSubCon' => $request->IsSubCon,
        //                 'SharingTask' => $request->SharingTask,
        //                 'DiscPct' =>
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->OperationCost *
        //                             (int) $request->OperationHour)) *
        //                     100,
        //                 'CreatedBy' => $request->CreatedBy,
        //             ]);
        //         }

        //         Arbeginbalancehdr::firstOrCreate(
        //             [
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'DocNo' => $docNoArbegin,
        //             ],
        //             [
        //                 'ProfitCenterCode' => '200',
        //                 'DocDate' => $invdate,
        //                 'CustomerCode' => $request->CustomerCodeBill,
        //                 'AccountNo' => $accountNo,
        //                 'DueDate' => $duedate,
        //                 'TOPCode' => $request->TOPCode,
        //                 'Amount' => 0,
        //                 'SalesCode' => '',
        //                 'LeasingCode' => '',
        //                 'Status' => 0,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //                 'PrintSeq' => '1',
        //             ]
        //         );

        //         Arbeginbalancedtl::firstOrCreate(
        //             [
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'DocNo' => $docNoArbegin,
        //             ],
        //             [
        //                 'SeqNo' => '1',
        //                 'AccountNo' => $accountNo,
        //                 'Description' => '',
        //                 'Amount' => 0,
        //                 'Status' => '',
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //             ]
        //         );

        //         $this->updateHeader(
        //             $request->InvDocNo,
        //             $servno,
        //             $request->Remarks,
        //             $request->Amount
        //         );
        //     }

        //     return response()->json(
        //         [
        //             'data' => 0,
        //         ],
        //         200
        //     );
        // } else {
        //     if (
        //         $request->Remarks == 'Sparepart' or
        //         $request->Remarks == 'Oil' or
        //         $request->Remarks == 'Material'
        //     ) {
        //         $partseq = Svtrnsrvitem::where(
        //             'CompanyCode',
        //             $service->CompanyCode
        //         )
        //             ->where('BranchCode', $service->BranchCode)
        //             ->where('ProductType', $service->ProductType)
        //             ->where('ServiceNo', $service->ServiceNo)
        //             ->where('PartNo', $request->PartNo)
        //             ->orderBy('PartSeq', 'DESC')
        //             ->first();
        //         if ($partseq == null) {
        //             $partseq_no = 1;
        //             $sss = $this->noUrut(
        //                 'SSS',
        //                 $branchcode,
        //                 $request->CompanyCode
        //             );
        //         } else {
        //             $partseq_no = $partseq->PartSeq + 1;
        //             $sss = $partseq->SupplySlipNo;
        //         }

        //         $svtrnsrvitem = Svtrnsrvitem::where(
        //             'CompanyCode',
        //             $service->CompanyCode
        //         )
        //             ->where('BranchCode', $service->BranchCode)
        //             ->where('ProductType', $service->ProductType)
        //             ->where('ServiceNo', $service->ServiceNo)
        //             ->where('PartNo', $request->PartNo)
        //             ->where('PartSeq', $partseq_no)
        //             ->first();
        //         if ($svtrnsrvitem == null) {
        //             Svtrnsrvitem::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'ServiceNo' => $service->ServiceNo,
        //                 'PartNo' => $request->PartNo,
        //                 'PartSeq' => $partseq_no,
        //                 'DemandQty' => $request->DemandQty,
        //                 'SupplyQty' => $request->SupplyQty,
        //                 'ReturnQty' => $request->ReturnQty,
        //                 'CostPrice' => $request->CostPrice,
        //                 'RetailPrice' => (int) $request->RetailPrice,
        //                 'TypeOfGoods' => $request->TypeOfGoods,
        //                 'BillType' => $request->BillType,
        //                 'SupplySlipNo' => $sss,
        //                 'SupplySlipDate' => $invdate,
        //                 'SSReturnNo' => null,
        //                 'SSReturnDate' => $invdate,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //                 'LastupdateBy' => $request->LastUpdateBy,
        //                 'LastupdateDate' => Carbon::now(),
        //                 'DiscPct' => round(
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->RetailPrice *
        //                             (float) $request->SupplyQty)) *
        //                         100,
        //                     2
        //                 ),
        //                 'MechanicID' => $request->MechanicID,
        //             ]);

        //             Svtrninvitem::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'InvoiceNo' => $service->InvoiceNo,
        //                 'PartNo' => $request->PartNo,
        //                 'MovingCode' => $request->MovingCode,
        //                 'ABCClass' => $request->ABCClass,
        //                 'SupplyQty' => $request->SupplyQty,
        //                 'ReturnQty' => $request->ReturnQty,
        //                 'CostPrice' => $request->CostPrice,
        //                 'RetailPrice' => (int) $request->RetailPrice,
        //                 'TypeOfGoods' => $request->TypeOfGoods,
        //                 'DiscPct' => round(
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->RetailPrice *
        //                             (float) $request->SupplyQty)) *
        //                         100,
        //                     2
        //                 ),
        //                 'MechanicID' => $request->MechanicID,
        //                 'CreatedBy' => $request->CreatedBy,
        //             ]);

        //             Svtrninvitemdtl::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'InvoiceNo' => $service->InvoiceNo,
        //                 'PartNo' => $request->PartNo,
        //                 'SupplySlipNo' => $sss,
        //                 'SupplyQty' => $request->SupplyQty,
        //                 'CostPrice' => $request->CostPrice,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //             ]);

        //             $this->updateDetail(
        //                 $branchcode,
        //                 $service->ServiceNo,
        //                 $service->ProductType,
        //                 $request->OperationNo,
        //                 $request->PartNo,
        //                 $service->InvoiceNo,
        //                 $sss,
        //                 $request->AmountDiscount,
        //                 $request->RetailPrice,
        //                 $request->SupplyQty,
        //                 $request->OperationHour,
        //                 $request->OperationCost
        //             );
        //         }
        //     } else {
        //         $svtrntask = Svtrnsrvtask::where(
        //             'CompanyCode',
        //             $service->CompanyCode
        //         )
        //             ->where('BranchCode', $service->BranchCode)
        //             ->where('ProductType', $service->ProductType)
        //             ->where('ServiceNo', $service->ServiceNo)
        //             ->where('OperationNo', $request->OperationNo)
        //             ->first();

        //         if ($svtrntask == null) {
        //             Svtrnsrvtask::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'ServiceNo' => $service->ServiceNo,
        //                 'OperationNo' => $request->OperationNo,
        //                 'OperationHour' => $request->OperationHour,
        //                 'OperationCost' => $request->OperationCost,
        //                 'IsSubCon' => $request->IsSubCon,
        //                 'SubConPrice' => $request->SubConPrice,
        //                 'PONo' => '',
        //                 'ClaimHour' => $request->ClaimHour,
        //                 'TypeOfGoods' => $request->TypeOfGoods,
        //                 'BillType' => $request->BillType,
        //                 'SharingTask' => $request->SharingTask,
        //                 'TaskStatus' => $request->TaskStatus,
        //                 'StartService' => $invdate,
        //                 'FinishService' => $invdate,
        //                 'CreatedBy' => $request->CreatedBy,
        //                 'CreatedDate' => Carbon::now(),
        //                 'LastupdateBy' => $request->LastUpdateBy,
        //                 'LastupdateDate' => Carbon::now(),
        //                 'DiscPct' => round(
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->RetailPrice *
        //                             (float) $request->SupplyQty)) *
        //                         100,
        //                     2
        //                 ),
        //             ]);

        //             Svtrninvtask::create([
        //                 'CompanyCode' => $request->CompanyCode,
        //                 'BranchCode' => $branchcode,
        //                 'ProductType' => $request->ProductType,
        //                 'InvoiceNo' => $service->InvoiceNo,
        //                 'OperationNo' => $request->OperationNo,
        //                 'OperationHour' => $request->OperationHour,
        //                 'ClaimHour' => $request->ClaimHour,
        //                 'OperationCost' => $request->OperationCost,
        //                 'SubConPrice' => $request->SubConPrice,
        //                 'IsSubCon' => $request->IsSubCon,
        //                 'SharingTask' => $request->SharingTask,
        //                 'DiscPct' => round(
        //                     ((int) $request->AmountDiscount /
        //                         ((int) $request->RetailPrice *
        //                             (float) $request->SupplyQty)) *
        //                         100,
        //                     2
        //                 ),
        //                 'CreatedBy' => $request->CreatedBy,
        //             ]);
        //         }
        //     }

        //     Arbeginbalancehdr::firstOrCreate([
        //         'CompanyCode' => $request->CompanyCode,
        //         'BranchCode' => $branchcode,
        //         'DocNo' => $docNoArbegin,
        //         'ProfitCenterCode' => '200',
        //         'DocDate' => $invdate,
        //         'CustomerCode' => $request->CustomerCode,
        //         'AccountNo' => $accountNo,
        //         'DueDate' => $duedate,
        //         'TOPCode' => $request->TOPCode,
        //         'Amount' => 0,
        //         'SalesCode' => '',
        //         'LeasingCode' => '',
        //         'Status' => 0,
        //         'CreatedBy' => $request->CreatedBy,
        //         'CreatedDate' => Carbon::now(),
        //         'PrintSeq' => '1',
        //     ]);

        //     Arbeginbalancedtl::firstOrCreate([
        //         'CompanyCode' => $request->CompanyCode,
        //         'BranchCode' => $branchcode,
        //         'DocNo' => $docNoArbegin,
        //         'SeqNo' => '1',
        //         'AccountNo' => $accountNo,
        //         'Description' => '',
        //         'Amount' => 0,
        //         'Status' => '',
        //         'CreatedBy' => $request->CreatedBy,
        //         'CreatedDate' => Carbon::now(),
        //     ]);

        //     $this->updateDetail(
        //         $branchcode,
        //         $service->ServiceNo,
        //         $service->ProductType,
        //         $service->OperationNo,
        //         $request->PartNo,
        //         $service->InvoiceNo,
        //         $service->SupplySlipNo,
        //         $request->AmountDiscount,
        //         $request->RetailPrice,
        //         $request->SupplyQty,
        //         $request->OperationHour,
        //         $request->OperationCost
        //     );

        //     $this->updateHeader(
        //         $request->InvDocNo,
        //         $service->ServiceNo,
        //         $request->Remarks,
        //         $request->Amount
        //     );

        //     return response()->json(
        //         [
        //             'data' => 1,
        //         ],
        //         200
        //     );
        // }

        return response()->json(
            [
                'data' => 1,
            ],
            200
        );
    }

    public function updateDetail(
        $branch,
        $serno,
        $productType,
        $operationNo,
        $partno,
        $invno,
        $slip,
        $discountAmount,
        $retailPrice,
        $supplyQty,
        $opHour,
        $opCost
    ) {
        $amtItem = (int) $retailPrice * $supplyQty;
        $amtSrv = (float) $opHour * (int) $opCost;

        $discPct = ((int) $discountAmount / (int) $amtItem) * 100;

        $discSrv = ((int) $discountAmount / (int) $amtSrv) * 100;

        Svtrnsrvitem::where('BranchCode', $branch)
            ->where('ProductType', $productType)
            ->where('ServiceNo', $serno)
            ->where('PartNo', $partno)
            ->where('SupplySlipNo', $slip)
            ->update([
                'DiscPct' => round($discPct, 2),
            ]);

        Svtrninvitem::where('BranchCode', $branch)
            ->where('ProductType', $productType)
            ->where('InvoiceNo', $invno)
            ->where('PartNo', $partno)
            ->update([
                'DiscPct' => round($discPct, 2),
            ]);

        Svtrnsrvtask::where('BranchCode', $branch)
            ->where('ProductType', $productType)
            ->where('ServiceNo', $serno)
            ->where('OperationNo', $operationNo)
            ->update([
                'DiscPct' => round($discSrv, 2),
            ]);
        Svtrninvtask::where('BranchCode', $branch)
            ->where('ProductType', $productType)
            ->where('InvoiceNo', $invno)
            ->where('OperationNo', $operationNo)
            ->update([
                'DiscPct' => round($discSrv, 2),
            ]);
    }

    public function updateHeader($invno, $serno, $remaks, $amount)
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
        $totaldppamount = $amount;
        $totalppnamount = 0;
        $totalsrvamount = 0;

        if (
            $remaks == 'Sparepart' or
            $remaks == 'Oil' or
            $remaks == 'Material'
        ) {
            $detail = Svtrnsrvitem::where('ServiceNo', $serno)->get();
            foreach ($detail as $row) {
                $partdispct = $row->DiscPct;

                $sum = $row->RetailPrice * $row->SupplyQty;

                $partsgrossamt = $partsgrossamt + $sum;

                $partsdiscamt = $row->AmountDiscount;

                $partsdppamt1 = $sum - $row->AmountDiscount;
                $partsdppamt = $partsdppamt + $partsdppamt1;
            }

            Svtrnservice::where('InvDocNo', $invno)->update([
                'PartDiscPct' => $partdispct,
                'PartsGrossAmt' => $partsgrossamt,
                'PartsDiscAmt' => $partsdiscamt,
                'PartsDppAmt' => $partsdppamt,
            ]);

            Svtrninvoice::where('InvDocNo', $invno)->update([
                'PartsDiscPct' => $partdispct,
                'PartsGrossAmt' => $partsgrossamt,
                'PartsDiscAmt' => $partsdiscamt,
                'PartsDppAmt' => $partsdppamt,
            ]);
        } else {
            $detail = Svtrnsrvtask::where('ServiceNo', $serno)->get();
            foreach ($detail as $row) {
                // $labordiscpct = $labordiscpct + $row->DiscPct;
                $labordiscpct = $row->DiscPct;
                $sum = $row->OperationHour * $row->OperationCost;

                $laborgrossamt = $laborgrossamt + $sum;

                $labordiscamt = $row->AmountDiscount;

                $labordppamt1 = $sum - $row->AmountDiscount;
                $labordppamt = $labordppamt + $labordppamt1;
            }

            Svtrnservice::where('InvDocNo', $invno)->update([
                'LaborDiscPct' => $labordiscpct,
                'LaborGrossAmt' => $laborgrossamt,
                'LaborDiscAmt' => $labordiscamt,
                'LaborDppAmt' => $labordppamt,
            ]);

            Svtrninvoice::where('InvDocNo', $invno)->update([
                'LaborDiscPct' => $labordiscpct,
                'LaborGrossAmt' => $laborgrossamt,
                'LaborDiscAmt' => $labordiscamt,
                'LaborDppAmt' => $labordppamt,
            ]);
        }

        $service = Svtrnservice::where('InvDocNo', $invno)->first();
        if ($service) {
            // docno arbegin
            $invnoEx = explode('/', $invno);
            $docFirst = substr($invnoEx[0], 1, 3);
            $docNoArbegin =
                $docFirst .
                '/' .
                $invnoEx[3] .
                '/' .
                $invnoEx[2] .
                $invnoEx[1] .
                $invnoEx[4];

            // $totaldppamount = $labordppamt + $partsdppamt;
            if ($docFirst == 'SIT') {
                $totalppnamount = 0;
                $totalsrvamount = $totaldppamount + $totalppnamount;
            } else {
                $totalppnamount = 0.11 * $totaldppamount;
                $totalsrvamount = $totaldppamount + $totalppnamount;
            }

            Svtrnservice::where('InvDocNo', $invno)->update([
                'TotalDPPAmount' => $totaldppamount,
                'TotalPpnAmount' => $totalppnamount,
                'TotalSrvAmount' => $totalsrvamount,
            ]);

            Svtrninvoice::where('InvDocNo', $invno)->update([
                'TotalDppAmt' => $totaldppamount,
                'TotalPpnAmt' => $totalppnamount,
                'TotalSrvAmt' => $totalsrvamount,
            ]);

            Arbeginbalancehdr::where('DocNo', $docNoArbegin)->update([
                'Amount' => round($totalsrvamount),
            ]);

            Arbeginbalancedtl::where('DocNo', $docNoArbegin)->update([
                'Amount' => round($totalsrvamount),
            ]);
        }
    }
}
