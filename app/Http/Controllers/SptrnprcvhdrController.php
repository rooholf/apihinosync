<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Sptrnprcvhdr;
use App\Sptrnprcvhdrdtl;
use App\Gnmstdocument;

use App\Apbeginbalancehdr;
use App\Apbeginbalancedtl;
use App\Sptrnphpp;

use App\Transformers\SptrnprcvhdrTransformer; //transformer

use Carbon\Carbon;

class SptrnprcvhdrController extends Controller
{
    public function show(Request $request, Sptrnprcvhdr $sptrnprcvhdr)
    {
        $sptrnprcvhdr = $sptrnprcvhdr->where('ReferenceNo', $request->ReferenceNo)->first();

        if ($sptrnprcvhdr) {
            // return fractal()
            //     ->item($sptrnprcvhdr)
            //     ->transformWith(new Sptrnprcvhdrgitransformer)
            //     ->toArray();

            return response()->json([
                'data' => 1
            ], 200);
        } else {

            return response()->json([
                'data' => 0
            ], 200);
        }
    }

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
            'GRNo' => 'required',
        ]);

        // nomor WRSNo
        if ($request->WhsCodeDesc == 'WH NORMAL - MUARA BUNGO') {
            $branchcode = '002';
        } else {
            $branchcode = '000';
        }

        // grno header
        $docEx = explode("/", $request->GRNo);
        if ($docEx[0] == 'SPRS') {
            $docNoApbegin = 'SPR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];
        } elseif ($docEx[0] == 'WSRS') {
            $docNoApbegin = 'WSR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];
        }

        $grdateEx = explode("T", $request->GRDate);
        $grdate = $grdateEx[0].' '.$grdateEx[1];
        $duedateOdd = date("Y-m-d", strtotime("+1 month", strtotime($grdate)));
        $duedate = $duedateOdd.' '.$grdateEx[1];
    

        $header = Sptrnprcvhdr::where('ReferenceNo', $docNoApbegin)->first();
        // dd($header);
        if ($header == null) {
            $wrsno = $this->noUrut('WRL', $branchcode, $request->CompanyCode);
            $binningno = $this->noUrut('BNL', $branchcode, $request->CompanyCode);
            $docno = $this->noUrut('POS', $branchcode, $request->CompanyCode);
            $hppno = $this->noUrut('HPP', $branchcode, $request->CompanyCode);

            // echo $wrsno;die;
            $sptrnprcvhdr = Sptrnprcvhdr::firstOrCreate([
                'CompanyCode'=> $request->CompanyCode,
                'BranchCode'=> $branchcode,
                'WRSNo'=> $wrsno,
                'WRSDate'=> $grdate,
                'BinningNo'=> $binningno,
                'BinningDate'=> $grdate,
                'ReceivingType'=> $request->ReceivingType,
                'DNSupplierNo'=> $docno,
                'DNSupplierDate'=> $grdate,
                'TransType'=> $request->TransType,
                'SupplierCode'=> $request->SupplierCode,
                'ReferenceNo'=> $docNoApbegin,
                'ReferenceDate'=> $grdate,
                'TotItem'=> $request->TotItem,
                'TotWRSAmt'=> $request->TotWRSAmt,
                'Status'=> $request->Status,
                'PrintSeq'=> $request->PrintSeq,
                'TypeOfGoods'=> $request->TypeOfGoods,
                'CreatedBy'=> $request->CreatedBy,
                'CreatedDate'=> Carbon::now(),
                'LastUpdateBy'=> $request->LastUpdateBy,
                'LastUpdateDate'=> Carbon::now(),
                'isLocked'=> $request->isLocked,
                'LockingBy'=> $request->LockingBy,
                'LockingDate'=> Carbon::now(),
                'GRNo' => $request->GRNo,
            ]);
                

            
            if ($sptrnprcvhdr) {
                // detail header
                $detail = Sptrnprcvhdrdtl::where('CompanyCode', $request->CompanyCode)
                                        ->where('BranchCode', $branchcode)
                                        ->where('WRSNo', $wrsno)
                                        ->where('PartNo', $request->PartNo)
                                        ->first();
                if (!$detail) {
                    Sptrnprcvhdrdtl::create([
                        'CompanyCode'=> $request->CompanyCode,
                        'BranchCode'=> $branchcode,
                        'WRSNo'=> $wrsno,
                        'PartNo'=> $request->PartNo,
                        'DocNo'=> $docno,
                        'DocDate'=> $grdate,
                        'WarehouseCode'=> $request->WarehouseCode,
                        'LocationCode'=> $request->LocationCode,
                        'BoxNo'=> $request->BoxNo,
                        'ReceivedQty'=> $request->ReceivedQty,
                        'PurchasePrice'=> $request->PurchasePrice,
                        'CostPrice'=> $request->CostPrice,
                        'DiscPct'=> $request->DiscPct,
                        'ABCClass'=> $request->ABCClass,
                        'MovingCode'=> $request->MovingCode,
                        'ProductType'=> $request->ProductType,
                        'PartCategory'=> $request->PartCategory,
                        'CreatedBy'=> $request->CreatedBy,
                        'CreatedDate'=> Carbon::now(),
                        'LastUpdateBy'=> $request->LastUpdateBy,
                        'LastUpdateDate'=> Carbon::now(),
                    ]);
                }
                    

                // Sptrnphpp::create([
                //     'CompanyCode'=> $request->CompanyCode,  
                //     'BranchCode'=> $branchcode, 
                //     'HPPNo'=> $hppno, 
                //     'HPPDate'=> Carbon::now(), 
                //     'WRSNo'=> $wrsno, 
                //     'WRSDate'=> Carbon::now(),
                //     'ReferenceNo'=> $request->ReferenceNo, 
                //     'ReferenceDate'=> Carbon::now(), 
                //     'TotPurchAmt'=> $request->TotWRSAmt, 
                //     'TotNetPurchAmt'=> $request->TotWRSAmt, 
                //     'TotTaxAmt'=> $request->TotTaxAmt, 
                //     'TaxNo'=> $request->TaxNo, 
                //     'TaxDate'=> Carbon::now(), 
                //     'MonthTax'=> $request->MonthTax, 
                //     'YearTax'=> $request->YearTax, 
                //     'DueDate'=> Carbon::now(),  
                //     'DiffNetPurchAmt'=> $request->DiffNetPurchAmt, 
                //     'DiffTaxAmt'=> $request->DiffTaxAmt, 
                //     'TotHPPAmt'=> $request->TotHPPAmt, 
                //     'CostPrice'=> 0,  
                //     'PrintSeq'=> $request->PrintSeq,  
                //     'TypeOfGoods'=> $request->TypeOfGoods, 
                //     'Status'=> 2, 
                //     'CreatedBy'=> $request->CreatedBy, 
                //     'CreatedDate'=> Carbon::now(), 
                //     'LastUpdateBy'=> $request->LastUpdateBy, 
                //     'LastUpdateDate'=> Carbon::now(), 

                // ]);

                $apbeginbalancehdr = Apbeginbalancehdr::where('CompanyCode', $request->CompanyCode)
                                                    ->where('BranchCode', $branchcode)
                                                    ->where('DocNo', $docNoApbegin)
                                                    ->first();

                if (!$apbeginbalancehdr) {
                    Apbeginbalancehdr::create([
                        'CompanyCode'=> $request->CompanyCode,
                        'BranchCode'=> $branchcode,
                        'DocNo'=> $docNoApbegin,
                        'ProfitCenterCode'=> '300',
                        'DocDate'=> $grdate,
                        'SupplierCode'=> $request->SupplierCode,
                        'AccountNo'=> '004.000.000.00000.300000.000.000',
                        'DueDate'=> $duedate,
                        'TOPCode'=> 'C30',
                        'Amount'=> $request->TotWRSAmt,
                        'Status'=> '0',
                        'CreatedBy'=> $request->CreatedBy,
                        'CreatedDate'=> Carbon::now(),
                        'PrintSeq'=> '1',
                    ]);
                }


                $apbeginbalancedtl = Apbeginbalancedtl::where('CompanyCode', $request->CompanyCode)
                                                ->where('BranchCode', $branchcode)
                                                ->where('DocNo', $docNoApbegin)
                                                ->first();

                if (!$apbeginbalancedtl) {
                    Apbeginbalancedtl::create([
                        'CompanyCode'=> $request->CompanyCode,
                        'BranchCode'=> $branchcode,
                        'DocNo'=> $docNoApbegin,
                        'SeqNo'=> '1',
                        'AccountNo'=> '004.000.000.00000.300000.000.000',
                        'Description'=> $request->ReferenceNo,
                        'Amount'=> $request->TotWRSAmt,
                        'Status'=> '0',
                        'CreatedBy'=> $request->CreatedBy,
                        'CreatedDate'=> Carbon::now(),
                    ]);
                }
                // apibegin detail
                    


                $this->updateTotItem($wrsno, $request->GRNo);

            }

            return response()->json([
                'data' => 0
            ], 200);

            // return fractal()
            //         ->item($sptrnprcvhdr)
            //         ->transformWith(new SptrnprcvhdrTransformer)
            //         ->toArray();
        } else {
            $header2 = Sptrnprcvhdrdtl::where('CompanyCode', '=', $request->CompanyCode)
                        ->where('BranchCode','=', $branchcode)
                        ->where('WRSNo','=', $header->WRSNo)
                        ->where('PartNo','=', $request->PartNo)
                        ->where('DocNo','=', $header->DNSupplierNo)
                        ->where('BoxNo','=', $request->BoxNo)
                        ->where('LocationCode','=', $request->LocationCode)
                        ->first();

            // dd($header2);

            if ($header2 == null) {
                Sptrnprcvhdrdtl::create([
                    'CompanyCode'=> $request->CompanyCode,
                    'BranchCode'=> $branchcode,
                    'WRSNo'=> $header->WRSNo,
                    'PartNo'=> $request->PartNo,
                    'DocNo'=> $header->DNSupplierNo,
                    'DocDate'=> $grdate,
                    'WarehouseCode'=> $request->WarehouseCode,
                    'LocationCode'=> $request->LocationCode,
                    'BoxNo'=> $request->BoxNo,
                    'ReceivedQty'=> $request->ReceivedQty,
                    'PurchasePrice'=> $request->PurchasePrice,
                    'CostPrice'=> $request->CostPrice,
                    'DiscPct'=> $request->DiscPct,
                    'ABCClass'=> $request->ABCClass,
                    'MovingCode'=> $request->MovingCode,
                    'ProductType'=> $request->ProductType,
                    'PartCategory'=> $request->PartCategory,
                    'CreatedBy'=> $request->CreatedBy,
                    'CreatedDate'=> Carbon::now(),
                    'LastUpdateBy'=> $request->LastUpdateBy,
                    'LastUpdateDate'=> Carbon::now(),
                ]);

                $this->updateTotItem($header->WRSNo, $request->GRNo);
            }

            return response()->json([
                'data' => 1
            ], 200);
        }

            
    }

    public function addDetail(Request $request, Sptrnprcvhdrdtl $sptrnprcvhdrdtl, Gnmstdocument $gnmstdocument)
    {
        $this->validate($request, [
            'ReferenceNo' => 'required',
        ]);

        // dd($header);
        $header = Sptrnprcvhdr::where('ReferenceNo', $request->ReferenceNo)->first();

        if ($request->WhsCodeDesc == 'WH NORMAL - MUARA BUNGO') {
            $branchcode = '002';
        } else {
            $branchcode = '000';
        }

        // $gnmstdocument3 = $gnmstdocument->where('DocumentType', 'POS')
        //                             ->where('BranchCode', $branchcode)
        //                             ->first();

        // $thnpos = substr($gnmstdocument3->DocumentYear, 2, 2);
        // $nourut3 = sprintf("%06s", $gnmstdocument3->DocumentSequence);

        // $docno = 'POS/'.$thnpos.'/'.$nourut3;

        if ($header <> null) {
            $header2 = Sptrnprcvhdrdtl::where('CompanyCode', $request->CompanyCode)
                        ->where('BranchCode', $branchcode)
                        ->where('WRSNo', $header->WRSNo)
                        ->where('PartNo', $request->PartNo)
                        ->where('DocNo', $header->DNSupplierNo)
                        ->where('BoxNo', $request->BoxNo)
                        ->first();

            // dd($header2);

            if ($header2 == null) {
                $sptrnprcvhdrdtl = $sptrnprcvhdrdtl->firstOrCreate([
                    'CompanyCode'=> $request->CompanyCode,
                    'BranchCode'=> $branchcode,
                    'WRSNo'=> $header->WRSNo,
                    'PartNo'=> $request->PartNo,
                    'DocNo'=> $header->DNSupplierNo,
                    'DocDate'=> Carbon::now(),
                    'WarehouseCode'=> $request->WarehouseCode,
                    'LocationCode'=> $request->LocationCode,
                    'BoxNo'=> $request->BoxNo,
                    'ReceivedQty'=> $request->ReceivedQty,
                    'PurchasePrice'=> $request->PurchasePrice,
                    'CostPrice'=> $request->CostPrice,
                    'DiscPct'=> $request->DiscPct,
                    'ABCClass'=> $request->ABCClass,
                    'MovingCode'=> $request->MovingCode,
                    'ProductType'=> $request->ProductType,
                    'PartCategory'=> $request->PartCategory,
                    'CreatedBy'=> $request->CreatedBy,
                    'CreatedDate'=> Carbon::now(),
                    'LastUpdateBy'=> $request->LastUpdateBy,
                    'LastUpdateDate'=> Carbon::now(),
                ]);

                $this->updateTotItem($header->WRSNo);

                return response()->json([
                    'data' => 0
                ], 200);

            } else {
                return response()->json([
                    'data' => 1
                ], 200);
            }
        }


            

        // return fractal()
        //         ->item($header)
        //         ->transformWith(new SptrnprcvhdrTransformer)
        //         ->toArray();

    }

    public function updateTotItem($wrsno, $grno)
    {
        $total = 0;
        $item = 0;

        // header
        $detail = Sptrnprcvhdrdtl::where('WRSNo', $wrsno)->get();

        foreach ($detail as $row) {
            $grandtotal = ($row->PurchasePrice * $row->ReceivedQty)-(($row->PurchasePrice * $row->ReceivedQty) * $row->DiscPct/100);
            $total = $total + $grandtotal;

            $item = $item + 1;
        }

        $totAmt = $total * 1.1;

        spTrnPRcvHdr::where('WRSNo', $wrsno)
            ->update([
                'TotItem' => $item, 
                'TotWRSAmt' => $totAmt
            ]);


        // 
        $docEx = explode("/", $grno);
        if ($docEx[0] == 'SPRS') {
            $docNoApbegin = 'SPR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];
        } elseif ($docEx[0] == 'WSRS') {
            $docNoApbegin = 'WSR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];
        }
        // $docNoApbegin = 'SPR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];

        Apbeginbalancehdr::where('DocNo', $docNoApbegin)
            ->update([
                'Amount' => $total
            ]);

        Apbeginbalancedtl::where('DocNo', $docNoApbegin)
            ->update([
                'Amount' => $total
            ]);

    }
}
