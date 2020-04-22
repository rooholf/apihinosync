<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Gnmstdocument;
use App\Sptrnsinvoicehdr;
use App\Sptrnsinvoicedtl;
use App\Sptrnsfpjhdr;
use App\Sptrnsfpjdtl;
use App\Sptrnsfpjinfo;
use App\Arbeginbalancehdr;
use App\Arbeginbalancedtl;

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
        $duedateOdd = date("Y-m-d", strtotime("+1 month", strtotime($invdate)));
        $duedate = $duedateOdd.' '.$invdateEx[1];

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
				'DocNo' => $docno,
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

        		Sptrnsfpjhdr::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $request->BranchCode,
					'FPJNo' => $fpjno,
					'FPJDate' => $invdate,
					'TPTrans' => $request->TPTrans,
					'FPJGovNo' => $request->FPJGovNo,
					'FPJSignature' => $invdate,
					'FPJCentralNo' => NULL,
					'FPJCentralDate' => $request->FPJCentralDate,
					'DeliveryNo' => $request->DeliveryNo,
					'InvoiceNo' => $invno,
					'InvoiceDate' => $invdate,
					'PickingSlipNo' => $pickingno,
					'PickingSlipDate' => $invdate,
					'TransType' => $request->TransType,
					'CustomerCode' => $request->CustomerCode,
					'CustomerCodeBill' => $request->CustomerCodeBill,
					'CustomerCodeShip' => $request->CustomerCodeShip,
					'TOPCode' => $request->TOPCode,
					'TOPDays' => $request->TOPDays,
					'DueDate' => $duedate,
					'TotSalesQty' => 0,
					'TotSalesAmt' => 0,
					'TotDiscAmt' => 0,
					'TotDPPAmt' => 0,
					'TotPPNAmt' => 0,
					'TotFinalSalesAmt' => 0,
					'isPKP' => $request->isPKP,
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
        		]);

        		Sptrnsfpjdtl::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $request->BranchCode,
					'FPJNo' => $fpjno,
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
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
        		]);

        		Sptrnsfpjinfo::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $request->BranchCode,
					'FPJNo' => $fpjno,
					'CustomerName' => $request->CustomerName,
					'Address1' => $request->Address1,
					'Address2' => $request->Address2,
					'Address3' => $request->Address3,
					'Address4' => $request->Address4,
					'isPKP' => $request->isPKP,
					'NPWPNo' => $request->NPWPNo,
					'SKPNo' => $request->SKPNo,
					'SKPDate' => $request->SKPDate,
					'NPWPDate' => $request->NPWPDate,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),

        		]);



        		$this->updateHeader($invno, $fpjno);
        	}

        	return response()->json([
                'data' => 0
            ], 200);

        } else {
        	$detail = Sptrnsinvoicedtl::where('CompanyCode', $header->CompanyCode)
        							->where('BranchCode', $header->BranchCode)
        							->where('InvoiceNo', $header->InvoiceNo)
        							->where('WarehouseCode', $request->WarehouseCode)
        							->where('PartNo', $request->PartNo)
        							->where('PartNoOriginal', $request->PartNo)
        							->where('DocNo', $header->DocNo)
        							->first();
        	if ($detail == null) {
        		Sptrnsinvoicedtl::firstOrCreate([
	    			'CompanyCode' => $header->CompanyCode,
					'BranchCode' => $header->BranchCode,
					'InvoiceNo' => $header->InvoiceNo,
					'WarehouseCode' => $request->WarehouseCode,
					'PartNo' => $request->PartNo,
					'PartNoOriginal' => $request->PartNo,
					'DocNo' => $header->DocNo,
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

	    		Sptrnsfpjdtl::firstOrCreate([
        			'CompanyCode' => $header->CompanyCode,
					'BranchCode' => $header->BranchCode,
					'FPJNo' => $header->FPJNo,
					'WarehouseCode' => $request->WarehouseCode,
					'PartNo' => $request->PartNo,
					'PartNoOriginal' => $request->PartNo,
					'DocNo' => $header->DocNo,
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
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
        		]);

	    		$this->updateHeader($header->InvoiceNo, $header->FPJNo);
        	}

	        	

        	return response()->json([
                'data' => 1
            ], 200);
        }


    }

    public function updateHeader($invno, $fpjno)
    {
    	$totqty = 0;
    	$totamt = 0;
    	$totdisc = 0;
    	$totdpp = 0;
    	$totppn = 0;
    	$totfinal = 0;

    	$detail = Sptrnsinvoicedtl::where('InvoiceNo', $invno)->get();
    	foreach ($detail as $row) {
    		$totqty = $totqty + $row->QtyBill;
    		$totamt = $totamt + $row->SalesAmt;
    		$totdisc = $totdisc + $row->DiscAmt;
    		$totdpp = $totdpp + $row->NetSalesAmt;
    		$totppn = $totppn + $row->PPNAmt;
    		$totfinal = $totfinal + $row->TotSalesAmt;
    	}

    	Sptrnsinvoicehdr::where('InvoiceNo', $invno)
    				->update([
    					'TotSalesQty' => $totqty, 
    					'TotSalesAmt' => $totamt, 
    					'TotDiscAmt' => $totdisc, 
    					'TotDPPAmt' => $totdpp, 
    					'TotPPNAmt' => $totppn, 
    					'TotFinalSalesAmt' => $totfinal,
    				]);

    	Sptrnsfpjhdr::where('FPJNo', $fpjno)
    				->update([
    					'TotSalesQty' => $totqty, 
    					'TotSalesAmt' => $totamt, 
    					'TotDiscAmt' => $totdisc, 
    					'TotDPPAmt' => $totdpp, 
    					'TotPPNAmt' => $totppn, 
    					'TotFinalSalesAmt' => $totfinal,
    				]);

    }
}
