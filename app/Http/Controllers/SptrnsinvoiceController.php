<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Sptrnsinvoicehdr;

use Carbon\Carbon;

class SptrnsinvoiceController extends Controller
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
            'InvoiceNo' => 'required',
        ]);

        if ($request->Dealer == 'Sparepart - JIM Muara Bungo') {
            $branchcode = '002';
        } else {
            $branchcode = '000';
        }

        // date
        $invdateEx = explode("T", $request->InvoiceDate);

        $invdate = $invdateEx[0].' '.$invdateEx[1];


        $header = Sptrnsinvoicehdr::where('CompanyCode', $request->CompanyCode)
        						->where('BranchCode', $branchcode)
        						->where('InvNo', $request->InvoiceNo)
        						->first();

        if ($header == null) {
        	$invno = $this->noUrut('INV', $branchcode, $request->CompanyCode);
        	$pickingno = $this->noUrut('PLS', $branchcode, $request->CompanyCode);
        	$fpjno = $this->noUrut('FPJ', $branchcode, $request->CompanyCode);

        	$sptrnsinvoicehdr = Sptrnsinvoicehdr::firstOrCreate([
        		'CompanyCode' => $request->CompanyCode,
				'BranchCode' => $branchcode,
				'InvoiceNo' => $invno,
				'InvoiceDate' => $invdate,
				'PickingSlipNo' => $pickingno,
				'PickingSlipDate' => $invdate,
				'FPJNo' => $fpjno,
				'FPJDate' => $invdate,
				'TransType' => $request->TransType,
				'SalesType' => $request->SalesType,
				'CustomerCode' => $request->CustomerCode,
				'CustomerCodeBill' => $request->CustomerCodeBill,
				'CustomerCodeShip' => $request->CustomerCodeShip,
				'TotSalesQty' => 0,
				'TotSalesAmt' => 0,
				'TotDiscAmt' => 0,
				'TotDPPAmt' => 0,
				'TotPPNAmt' => 0,
				'TotFinalSalesAmt' => 0,
				'Status' => $request->Status,
				'PrintSeq' => $request->PrintSeq,
				'TypeOfGoods' => $request->TypeOfGoods,
				'CreatedBy' => $request->CreatedBy,
				'CreatedDate' => Carbon::now(),
				'LastUpdateBy' => $request->LastUpdateBy,
				'LastUpdateDate' => Carbon::now(),
				'isLocked' => $request->isLocked,
				'LockingBy' => $request->LockingBy,
				'LockingDate' => Carbon::now(),
				'InvNo' => $request->InvoiceNo,
        	]);

        	return response()->json([
                'data' => 0
            ], 200);

        } else {
        	return response()->json([
                'data' => 1
            ], 200);
        }


    }
}
