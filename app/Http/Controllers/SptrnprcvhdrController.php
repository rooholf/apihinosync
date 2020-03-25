<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Sptrnprcvhdr;
use App\Sptrnprcvhdrdtl;
use App\Gnmstdocument;
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

    public function add(Request $request, Sptrnprcvhdr $sptrnprcvhdr, Sptrnprcvhdrdtl $sptrnprcvhdrdtl, Gnmstdocument $gnmstdocument)
    {
        $this->validate($request, [
            'ReferenceNo' => 'required',
        ]);

        $header = Sptrnprcvhdr::where('ReferenceNo', $request->ReferenceNo)->first();

        if (!$header) {
            // nomor WRSNo
            if ($request->WhsCodeDesc == 'WH NORMAL - HINO JAMBI') {
                $branchcode = '000';
            } else {
                $branchcode = '002';
            }

            $gnmstdocument = $gnmstdocument->where('DocumentType', 'WRL')
                                        ->where('BranchCode', $branchcode)
                                        ->first();

            $gnmstdocument2 = $gnmstdocument->where('DocumentType', 'BNL')
                                        ->where('BranchCode', $branchcode)
                                        ->first();

            $gnmstdocument3 = $gnmstdocument->where('DocumentType', 'POS')
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

            ]);

            if ($sptrnprcvhdr) {
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

                $this->updateTotItem($wrsno);

                DB::table('gnMstDocument')
                ->where('DocumentType', 'WRL')
                ->where('BranchCode', $branchcode)
                ->where('CompanyCode', $request->CompanyCode)
                ->update(['DocumentSequence' => $no1]);

                DB::table('gnMstDocument')
                ->where('DocumentType', 'BNL')
                ->where('BranchCode', $branchcode)
                ->where('CompanyCode', $request->CompanyCode)
                ->update(['DocumentSequence' => $no2]);

                DB::table('gnMstDocument')
                ->where('DocumentType', 'POS')
                ->where('BranchCode', $branchcode)
                ->where('CompanyCode', $request->CompanyCode)
                ->update(['DocumentSequence' => $no3]);

            }

            return fractal()
                    ->item($sptrnprcvhdr)
                    ->transformWith(new SptrnprcvhdrTransformer)
                    ->toArray();
        } else {
            return response()->json([
                'data' => 0
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

        if ($header) {
            $header2 = Sptrnprcvhdrdtl::where('CompanyCode', $request->CompanyCode)
                        ->where('BranchCode', $branchcode)
                        ->where('WRSNo', $header->WRSNo)
                        ->where('PartNo', $request->PartNo)
                        ->first();

            if (!$header2) {
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
                    'data' => 1
                ], 200);

            } else {
                return response()->json([
                    'data' => 0
                ], 200);
            }
        }


            

        // return fractal()
        //         ->item($header)
        //         ->transformWith(new SptrnprcvhdrTransformer)
        //         ->toArray();

    }

    public function updateTotItem($wrsno)
    {
        $total = 0;
        $item = 0;
        $detail = Sptrnprcvhdrdtl::where('WRSNo', $wrsno)->get();

        foreach ($detail as $row) {
            $grandtotal = ($row->PurchasePrice * $row->ReceivedQty)-(($row->ReceivedQty * $row->PurchasePrice) * $row->DiscPct/100);
            $total = $total + $grandtotal;

            $item++;
        }

        DB::table('spTrnPRcvHdr')
            ->where('WRSNo', $wrsno)
            ->update([
                'TotItem' => $item, 
                'TotWRSAmt' => $total
            ]);

    }
}
