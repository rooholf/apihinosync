<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Gnmstdocument;
use App\Sptrnsinvoicehdr;
use App\Sptrnsinvoicedtl;

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

        // accomulative
        $rinctax = $request->RetailPrice * 1.1;
		$disc = ($request->DiscAmt/$request->SalesAmt)*100;

		$total = $request->NetSalesAmt + $request->PPNAmt;


        $header = Sptrnsinvoicehdr::where('CompanyCode', $request->CompanyCode)
        						->where('BranchCode', $branchcode)
        						->where('InvNo', $request->InvoiceNo)
        						->first();

        if ($header == null) {
        	$invno = $this->noUrut('INV', $branchcode, $request->CompanyCode);
        	$pickingno = $this->noUrut('PLS', $branchcode, $request->CompanyCode);
        	$fpjno = $this->noUrut('FPJ', $branchcode, $request->CompanyCode);
        	$docno = $this->noUrut('SOC', $branchcode, $request->CompanyCode);

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

        	if ($sptrnsinvoicehdr) {
        		Sptrnsinvoicedtl::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'InvoiceNo' => $invno,
					'WarehouseCode' => $request->WarehouseCode,
					'PartNo' => $request->PartNo,
					'PartNoOriginal' => $request->PartNo,
					'DocNo' => $docno,
					'DocDate' => $invdate,
					'ReferenceNo' => $request->ReferenceNo,
					'ReferenceDate' => $invdate,
					'LocationCode' => $request->LocationCode,
					'QtyBill' => $request->QtyBill,
					'RetailPriceInclTax' => $rinctax,
					'RetailPrice' => $request->RetailPrice,
					'CostPrice' => $request->RetailPrice,
					'DiscPct' => $disc,
					'SalesAmt' => $request->SalesAmt,
					'DiscAmt' => $request->DiscAmt,
					'NetSalesAmt' => $request->NetSalesAmt,
					'PPNAmt' => $request->PPNAmt,
					'TotSalesAmt' => $total,
					'ProductType' => $request->ProductType,
					'PartCategory' => $request->PartCategory,
					'MovingCode' => $request->MovingCode,
					'ABCClass' => $request->ABCClass,
					'ExPickingListNo' => $request->ExPickingListNo,
					'ExPickingListDate' => $request->ExPickingListDate,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
        		]);
        	}

        	return response()->json([
                'data' => 0
            ], 200);

        } else {
        	Sptrnsinvoicedtl::firstOrCreate([
    			'CompanyCode' => $request->CompanyCode,
				'BranchCode' => $branchcode,
				'InvoiceNo' => $invno,
				'WarehouseCode' => $request->WarehouseCode,
				'PartNo' => $request->PartNo,
				'PartNoOriginal' => $request->PartNo,
				'DocNo' => $docno,
				'DocDate' => $invdate,
				'ReferenceNo' => $request->ReferenceNo,
				'ReferenceDate' => $invdate,
				'LocationCode' => $request->LocationCode,
				'QtyBill' => $request->QtyBill,
				'RetailPriceInclTax' => $rinctax,
				'RetailPrice' => $request->RetailPrice,
				'CostPrice' => $request->RetailPrice,
				'DiscPct' => $disc,
				'SalesAmt' => $request->SalesAmt,
				'DiscAmt' => $request->DiscAmt,
				'NetSalesAmt' => $request->NetSalesAmt,
				'PPNAmt' => $request->PPNAmt,
				'TotSalesAmt' => $total,
				'ProductType' => $request->ProductType,
				'PartCategory' => $request->PartCategory,
				'MovingCode' => $request->MovingCode,
				'ABCClass' => $request->ABCClass,
				'ExPickingListNo' => $request->ExPickingListNo,
				'ExPickingListDate' => $request->ExPickingListDate,
				'CreatedBy' => $request->CreatedBy,
				'CreatedDate' => Carbon::now(),
				'LastUpdateBy' => $request->LastUpdateBy,
				'LastUpdateDate' => Carbon::now(),
    		]);
    		
        	return response()->json([
                'data' => 1
            ], 200);
        }


    }

    public function updateTotItem()
    {

    }
}
