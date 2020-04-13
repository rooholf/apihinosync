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

    public function add(Request $request, Sptrnprcvhdr $sptrnprcvhdr, Sptrnprcvhdrdtl $sptrnprcvhdrdtl, Gnmstdocument $gnmstdocument, Apbeginbalancehdr $apbeginbalancehdr, Apbeginbalancedtl $Apbeginbalancedtl)
    {
        $this->validate($request, [
            'ReferenceNo' => 'required',
        ]);

        // nomor WRSNo
        if ($request->WhsCodeDesc == 'WH NORMAL - HINO JAMBI') {
            $branchcode = '000';
        } else {
            $branchcode = '002';
        }

        $header = Sptrnprcvhdr::where('GRNo', $request->GRNo)->count();

        if ($header < 1) {
            
            $gnmstdocument = Gnmstdocument::where('DocumentType', 'WRL')
                                        ->where('BranchCode', $branchcode)
                                        ->first();

            $gnmstdocument2 = Gnmstdocument::where('DocumentType', 'BNL')
                                        ->where('BranchCode', $branchcode)
                                        ->first();

            $gnmstdocument3 = Gnmstdocument::where('DocumentType', 'POS')
                                        ->where('BranchCode', $branchcode)
                                        ->first();

            // thn wrl
            $no1 = $gnmstdocument->DocumentSequence + 1;
            $thnwrl = substr($gnmstdocument->DocumentYear, 2, 2);
            $nourut = sprintf("%06s", $no1);

            $no2 = $gnmstdocument2->DocumentSequence + 1;
            $thnbnl = substr($gnmstdocument2->DocumentYear, 2, 2);
            $nourut2 = sprintf("%06s", $no2);

            $no3 = $gnmstdocument3->DocumentSequence + 1;
            $thnpos = substr($gnmstdocument3->DocumentYear, 2, 2);
            $nourut3 = sprintf("%06s", $no3);

            $wrsno = 'WRL/'.$thnwrl.'/'.$nourut;
            $binningno = 'BNL/'.$thnbnl.'/'.$nourut2;
            $docno = 'POS/'.$thnpos.'/'.$nourut3;

            Gnmstdocument::where('DocumentType', 'WRL')
            ->where('BranchCode', $branchcode)
            ->where('CompanyCode', $request->CompanyCode)
            ->update(['DocumentSequence' => $no1]);

            Gnmstdocument::where('DocumentType', 'BNL')
            ->where('BranchCode', $branchcode)
            ->where('CompanyCode', $request->CompanyCode)
            ->update(['DocumentSequence' => $no2]);

            Gnmstdocument::where('DocumentType', 'POS')
            ->where('BranchCode', $branchcode)
            ->where('CompanyCode', $request->CompanyCode)
            ->update(['DocumentSequence' => $no3]);

            // echo $wrsno;die;

            $sptrnprcvhdr = $sptrnprcvhdr->firstOrCreate([
                'CompanyCode'=> $request->CompanyCode,
                'BranchCode'=> $branchcode,
                'WRSNo'=> $wrsno,
                'WRSDate'=> Carbon::now(),
                'BinningNo'=> $binningno,
                'BinningDate'=> Carbon::now(),
                'ReceivingType'=> $request->ReceivingType,
                'DNSupplierNo'=> $docno,
                'DNSupplierDate'=> Carbon::now(),
                'TransType'=> $request->TransType,
                'SupplierCode'=> $request->SupplierCode,
                'ReferenceNo'=> $request->ReferenceNo,
                'ReferenceDate'=> Carbon::now(),
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
                $sptrnprcvhdrdtl = $sptrnprcvhdrdtl->firstOrCreate([
                    'CompanyCode'=> $request->CompanyCode,
                    'BranchCode'=> $branchcode,
                    'WRSNo'=> $wrsno,
                    'PartNo'=> $request->PartNo,
                    'DocNo'=> $docno,
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

                // grno header
                $docEx = explode("/", $request->GRNo);
                $docNoApbegin = 'SPR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];

                $apbeginbalancehdr = $apbeginbalancehdr->firstOrCreate([
                    'CompanyCode'=> $request->CompanyCode,
                    'BranchCode'=> $branchcode,
                    'DocNo'=> $docNoApbegin,
                    'ProfitCenterCode'=> '300',
                    'DocDate'=> Carbon::now(),
                    'SupplierCode'=> $request->SupplierCode,
                    'AccountNo'=> '004.000.000.00000.300000.000.000',
                    'DueDate'=> Carbon::now(),
                    'TOPCode'=> 'C30',
                    'Amount'=> $request->TotWRSAmt,
                    'Status'=> '0',
                    'CreatedBy'=> $request->CreatedBy,
                    'CreatedDate'=> Carbon::now(),
                    'PrintSeq'=> '1',
                ]);

                // apibegin detail
                $Apbeginbalancedtl = $Apbeginbalancedtl->firstOrCreate([
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
            $header2 = Sptrnprcvhdrdtl::where('CompanyCode', $request->CompanyCode)
                        ->where('BranchCode', $branchcode)
                        ->where('WRSNo', $header->WRSNo)
                        ->where('PartNo', $request->PartNo)
                        ->where('DocNo', $header->DNSupplierNo)
                        ->where('BoxNo', $request->BoxNo)
                        ->count();

            // dd($header2);

            if ($header2 < 1) {
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

        if ($request->WhsCodeDesc == 'WH NORMAL - HINO JAMBI') {
            $branchcode = '000';
        } else {
            $branchcode = '002';
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
            $grandtotal = ($row->PurchasePrice * $row->ReceivedQty)-(($row->ReceivedQty * $row->PurchasePrice) * $row->DiscPct/100);
            $total = $total + $grandtotal;

            $item++;
        }

        spTrnPRcvHdr::where('WRSNo', $wrsno)
            ->update([
                'TotItem' => $item, 
                'TotWRSAmt' => $total
            ]);


        // 
        $docEx = explode("/", $grno);
        $docNoApbegin = 'SPR/'. $docEx[3].'/'.$docEx[2].$docEx[1].$docEx[4];

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
