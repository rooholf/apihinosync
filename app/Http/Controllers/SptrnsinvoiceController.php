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
            $branchcode = '1002';
            $accountNo = '004.002.000.00000.300000.000.000';
        } else {
            $branchcode = '1000';
            $accountNo = '004.000.000.00000.300000.000.000';
        }

        // date
        $invdateEx = explode(" ", $request->InvoiceDate);

        $invdate = $invdateEx[0].' '.$invdateEx[1];
        $duedateOdd = date("Y-m-d", strtotime("+1 month", strtotime($invdate)));
        $duedate = $duedateOdd.' '.$invdateEx[1];

        // accomulative
        $rinctax = $request->RetailPrice * 1.1;

		

		// docno arbegin
		$invnoEx = explode("/", $request->InvoiceNo);
		$docFirst = substr($invnoEx[0], 1, 3);
		$docNoArbegin = $docFirst .'/'. $invnoEx[3].'/'.$invnoEx[2].$invnoEx[1].$invnoEx[4];


        $header = Sptrnsinvoicehdr::where('CompanyCode', $request->CompanyCode)
        						->where('BranchCode', $branchcode)
        						->where('InvNo', $request->InvoiceNo)
        						->first();
       //Untuk nembak AR 
       // dari sini 
     //    if($header){
     //    	Arbeginbalancehdr::firstOrCreate([
     //    			'CompanyCode' => $request->CompanyCode,
					// 'BranchCode' => $branchcode,
					// 'DocNo' => $docNoArbegin,
					// 'ProfitCenterCode' => '300',
					// 'DocDate' => $invdate,
					// 'CustomerCode' => $request->CustomerCode,
					// 'AccountNo' => $accountNo,
					// 'DueDate' => $duedate,
					// 'TOPCode' => $request->TOPCode,
					// 'Amount' => 0,
					// 'SalesCode' => '',
					// 'LeasingCode' => '',
					// 'Status' => 0,
					// 'CreatedBy' => $request->CreatedBy,
					// 'CreatedDate' => Carbon::now(),
					// 'PrintSeq' => '1',
     //    		]);

     //    		Arbeginbalancedtl::firstOrCreate([
     //    			'CompanyCode' => $request->CompanyCode,
					// 'BranchCode' => $branchcode,
					// 'DocNo' => $docNoArbegin,
					// 'SeqNo' => '1',
					// 'AccountNo' => $accountNo,
					// 'Description' => '',
					// 'Amount' => 0,
					// 'Status' => '',
					// 'CreatedBy' => $request->CreatedBy,
					// 'CreatedDate' => Carbon::now(),
     //    		]);
     //    }
        //Sampe sini
        	// $invno = $this->noUrut('INV', $branchcode, $request->CompanyCode);
        	// $pickingno = $this->noUrut('PLS', $branchcode, $request->CompanyCode);
        	// $fpjno = $this->noUrut('FPJ', $branchcode, $request->CompanyCode);
        	// $docno = $this->noUrut('SOC', $branchcode, $request->CompanyCode);

        if (!$header) {
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
				'TotDiscAmt' => $request->DiscAmt,
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
				'LockingBy' => '',
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
					'DiscPct' => $request->ItemDiscPct,
					'DiscAmt' => $request->ItemDiscAmt,
					'SalesAmt' =>$request->QtyBill * $request->RetailPrice,
					'NetSalesAmt' =>($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt,
					'PPNAmt' => $request->PPNAmt,
					'TotSalesAmt' =>(($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt) + $request->PPNAmt,
					'ProductType' => $request->ProductType,
					'PartCategory' => $request->PartCategory,
					'MovingCode' => $request->MovingCode,
					'ABCClass' => $request->ABCClass,
					'ExPickingListNo' => '',
					'ExPickingListDate' => $request->ExPickingListDate,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
        		]);

        		Sptrnsfpjhdr::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'FPJNo' => $fpjno,
					'FPJDate' => $invdate,
					'TPTrans' => $request->TPTrans,
					'FPJGovNo' => $request->FPJGovNo,
					'FPJSignature' => $invdate,
					'FPJCentralNo' => '',
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
					'TotDiscAmt' => $request->DiscAmt,
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
					'LockingBy' => '',
					'LockingDate' => Carbon::now(),
        		]);

        		Sptrnsfpjdtl::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
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
					'DiscPct' => $request->ItemDiscPct,
					'DiscAmt' => $request->ItemDiscAmt,
					'SalesAmt' => $request->SalesAmt,
					'NetSalesAmt' => ($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt,
					'PPNAmt' => $request->PPNAmt,
					'TotSalesAmt' => (($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt) + $request->PPNAmt,
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
					'BranchCode' => $branchcode,
					'FPJNo' => $fpjno,
					'CustomerName' => $request->CustomerName,
					'Address1' => str_replace(",", " ",  $request->Address1),
					'Address2' => str_replace(",", " ",  $request->Address2),
					'Address3' => str_replace(",", " ",  $request->Address3),
					'Address4' => '',
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

        		Arbeginbalancehdr::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'DocNo' => $docNoArbegin,
					'ProfitCenterCode' => '300',
					'DocDate' => $invdate,
					'CustomerCode' => $request->CustomerCode,
					'AccountNo' => $accountNo,
					'DueDate' => $duedate,
					'TOPCode' => $request->TOPCode,
					'Amount' => 0,
					'SalesCode' => '',
					'LeasingCode' => '',
					'Status' => 0,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'PrintSeq' => '1',
        		]);

        		Arbeginbalancedtl::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'DocNo' => $docNoArbegin,
					'SeqNo' => '1',
					'AccountNo' => $accountNo,
					'Description' => '',
					'Amount' => 0,
					'Status' => '',
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
        		]);


        		$this->updateDetail($branchcode, 
        							$InvNo, 
        							$request->WarehouseCode, 
        							$request->PartNo, 
        							$docno,
        							$request->QtyBill,
        							$request->RetailPrice,
        							$request->ItemDiscPct,
        							$request->ItemDiscAmt,
        							$fpjno,
        							$request->Amount);


        		$this->updateHeader($invno, $fpjno, $request->InvoiceNo, $branchcode, $request->Amount, $request->TotPPNAmt);
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
					'DiscPct' => $request->ItemDiscPct,
					'SalesAmt' => $request->QtyBill * $request->RetailPrice,
					'DiscAmt' => $request->ItemDiscAmt,
					'NetSalesAmt' => ($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt,
					'PPNAmt' => $request->PPNAmt,
					'TotSalesAmt' => (($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt) + $request->PPNAmt,
					'ProductType' => $request->ProductType,
					'PartCategory' => $request->PartCategory,
					'MovingCode' => $request->MovingCode,
					'ABCClass' => $request->ABCClass,
					'ExPickingListNo' => '',
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
					'DiscPct' => $request->ItemDiscPct,
					'SalesAmt' =>  $request->QtyBill * $request->RetailPrice,
					'DiscAmt' => $request->ItemDiscAmt,
					'NetSalesAmt' => ($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt,
					'PPNAmt' => $request->PPNAmt,
					'TotSalesAmt' => (($request->QtyBill * $request->RetailPrice) - $request->ItemDiscAmt)+$request->PPNAmt,
					'ProductType' => $request->ProductType,
					'PartCategory' => $request->PartCategory,
					'MovingCode' => $request->MovingCode,
					'ABCClass' => $request->ABCClass,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
        		]);

	    		

	    		$this->updateDetail($header->BranchCode, 
        							$header->InvoiceNo, 
        							$header->WarehouseCode, 
        							$header->PartNo, 
        							$header->DocNo,
        							$request->QtyBill,
        							$request->RetailPrice,
        							$request->ItemDiscPct,
        							$request->ItemDiscAmt,
        							$header->FPJNo,
        							$request->Amount);

	    		$this->updateHeader($header->InvoiceNo, $header->FPJNo, $header->InvNo, $header->BranchCode, $request->Amount, $request->TotPPNAmt);
        	} else {
        		
        		$this->updateDetail($detail->BranchCode, 
        							$detail->InvoiceNo, 
        							$detail->WarehouseCode, 
        							$detail->PartNo, 
        							$detail->DocNo,
        							$request->QtyBill,
        							$request->RetailPrice,
        							$request->ItemDiscPct,
        							$request->ItemDiscAmt,
        							$header->FPJNo,
        							$request->Amount);


        		$this->updateHeader($header->InvoiceNo, $header->FPJNo, $header->InvNo, $header->BranchCode, $request->Amount, $request->TotPPNAmt);
        	}

        	Sptrnsfpjhdr::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'FPJNo' => $header->FPJNo,
					'FPJDate' => $invdate,
					'TPTrans' => $request->TPTrans,
					'FPJGovNo' => $request->FPJGovNo,
					'FPJSignature' => $invdate,
					'FPJCentralNo' => '',
					'FPJCentralDate' => $request->FPJCentralDate,
					'DeliveryNo' => $request->DeliveryNo,
					'InvoiceNo' => $header->InvoiceNo,
					'InvoiceDate' => $invdate,
					'PickingSlipNo' => $header->PickingSlipNo,
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
					'TotDiscAmt' => $request->DiscAmt,
					'TotDPPAmt' => 0,
					'TotPPNAmt' => $request->TotPPNAmt,
					'TotFinalSalesAmt' => $request->Amount,
					'isPKP' => $request->isPKP,
					'Status' => $request->Status,
					'PrintSeq' => $request->PrintSeq,
					'TypeOfGoods' => $request->TypeOfGoods,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'LastUpdateBy' => $request->LastUpdateBy,
					'LastUpdateDate' => Carbon::now(),
					'isLocked' => $request->isLocked,
					'LockingBy' => '',
					'LockingDate' => Carbon::now(),
        		]);

        		Sptrnsfpjdtl::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
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
					'DiscPct' => $request->ItemDiscPct,
					'DiscAmt' => $request->ItemDiscAmt,
					'SalesAmt' => $request->SalesAmt,
					'NetSalesAmt' => $request->SalesAmt - $request->ItemDiscAmt,
					'PPNAmt' => $request->PPNAmt,
					'TotSalesAmt' => $request->Amount,
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
					'BranchCode' => $branchcode,
					'FPJNo' => $header->FPJNo,
					'CustomerName' => $request->CustomerName,
					'Address1' => $request->Address1,
					'Address2' => $request->Address2,
					'Address3' => $request->Address3,
					'Address4' => '',
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

        		Arbeginbalancehdr::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'DocNo' => $docNoArbegin,
					'ProfitCenterCode' => '300',
					'DocDate' => $invdate,
					'CustomerCode' => $request->CustomerCode,
					'AccountNo' => $accountNo,
					'DueDate' => $duedate,
					'TOPCode' => $request->TOPCode,
					'Amount' => 0,
					'SalesCode' => '',
					'LeasingCode' => '',
					'Status' => 0,
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
					'PrintSeq' => '1',
        		]);

        		Arbeginbalancedtl::firstOrCreate([
        			'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $branchcode,
					'DocNo' => $docNoArbegin,
					'SeqNo' => '1',
					'AccountNo' => $accountNo,
					'Description' => '',
					'Amount' => 0,
					'Status' => '',
					'CreatedBy' => $request->CreatedBy,
					'CreatedDate' => Carbon::now(),
        		]);
        	return response()->json([
                'data' => 1
            ], 200);
        }



    }




    public function updateDetail($branch, $invno, $warehouse, $partno, $docno, $qty, $retail, $discPct, $discAmt, $fpjno, $Amount){

    		$SalesAmount = $qty * $retail;
    		$NetSalesAmt = $SalesAmount - $discAmt;
    		$PPNAmt = $NetSalesAmt * 0.11;
    		$TotSalesAmt = 0;



    	Sptrnsinvoicedtl::where('BranchCode', $branch)
    							->where('InvoiceNo', $invno)
    							->where('WarehouseCode', $warehouse)
    							->where('PartNo', $partno)
    							->where('DocNo', $docno)
    							->update([
    								'DiscPct' => $discPct,
									'SalesAmt' => $qty * $retail,
									'DiscAmt' => $discAmt,
									'NetSalesAmt' => ($qty * $retail) - $discAmt,
									'PPNAmt' => (($qty * $retail) - $discAmt) *0.11,
									'TotSalesAmt' => $NetSalesAmt + $PPNAmt ,
    							]);

    	Sptrnsfpjdtl::where('BranchCode', $branch)
    							->where('FPJNo', $fpjno)
    							->where('WarehouseCode', $warehouse)
    							->where('PartNo', $partno)
    							->where('DocNo', $docno)
    							->update([
    								'DiscPct' => $discPct,
									'SalesAmt' => $qty * $retail,
									'DiscAmt' => $discAmt,
									'NetSalesAmt' => ($qty * $retail) - $discAmt,
									'PPNAmt' => (($qty * $retail) - $discAmt) *0.11,
									'TotSalesAmt' => $NetSalesAmt + $PPNAmt
    							]);


    }

    public function updateHeader($invno, $fpjno, $invodd, $branch, $Amount, $TotPPNAmt)
    {
    	$totqty = 0;
    	$totamt = 0;
    	$totdisc = 0;
    	$totdpp = 0;
    	$totppn = $TotPPNAmt;
    	$totfinal = $Amount;
    	

    	$detail = Sptrnsinvoicedtl::where('InvoiceNo', $invno)
    								->where('BranchCode', $branch)->get();
    	foreach ($detail as $row) {
    		$totqty = $row->QtyBill + $totqty;
    		$totamt = $row->SalesAmt + $totamt;
    		$totdpp = $row->NetSalesAmt + $totdpp;
    		$totdisc = $row->DiscAmt + $totdisc; 
    	}

    	Sptrnsinvoicehdr::where('InvoiceNo', $invno)
    				->update([
    					'TotSalesQty' => $totqty,
    					'TotDiscAmt' => $totdisc, 
    					'TotSalesAmt' => $totamt, 
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

    	// docno arbegin
		$invnoEx = explode("/", $invodd);
		$docFirst = substr($invnoEx[0], 1, 3);
		$docNoArbegin = $docFirst .'/'. $invnoEx[3].'/'.$invnoEx[2].$invnoEx[1].$invnoEx[4];

		Arbeginbalancehdr::where('DocNo', $docNoArbegin)
					->update([
						'Amount' => $totfinal,
					]);

		Arbeginbalancedtl::where('DocNo', $docNoArbegin)
					->update([
						'Amount' => $totfinal,
					]);

    }
}
