<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Sptrnprcvhdr;
use App\Gnmstdocument;
use App\Transformers\SptrnprcvhdrTransformer; //transformer

use Carbon\Carbon;

class SptrnprcvhdrController extends Controller
{
    public function show(Request $request, Sptrnprcvhdr $sptrnprcvhdr)
    {
        $sptrnprcvhdr = $sptrnprcvhdr->find($request->WRSNo);

        if ($sptrnprcvhdr) {
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

    public function add(Request $request, Sptrnprcvhdr $sptrnprcvhdr, Gnmstdocument $gnmstdocument)
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

        $gnmstdocument = $gnmstdocument->where('DocumentType', 'WRL')
                                    ->where('BranchCode', $branchcode)
                                    ->first();

        $gnmstdocument2 = $gnmstdocument->where('DocumentType', 'BNL')
                                    ->where('BranchCode', $branchcode)
                                    ->first();

        // thn wrl
        $thnwrl = substr($gnmstdocument->DocumentYear, 2, 2);
        $nourut = sprintf("%06s", $gnmstdocument->DocumentSequence + 1);

        $thnbnl = substr($gnmstdocument2->DocumentYear, 2, 2);
        $nourut2 = sprintf("%06s", $gnmstdocument2->DocumentSequence + 1);

        $wrsno = 'WRL/'.$thnwrl.'/'.$nourut;
        $binningno = 'BNL/'.$thnbnl.'/'.$nourut2;

        // echo $wrsno;die;


        $sptrnprcvhdr = $sptrnprcvhdr->firstOrCreate([
            'CompanyCode'=> $request->CompanyCode,
            'BranchCode'=> $request->BranchCode,
            'WRSNo'=> $wrsno,
            'WRSDate'=> $request->WRSDate,
            'BinningNo'=> $binningno,
            'BinningDate'=> Carbon::now(),
            'ReceivingType'=> $request->ReceivingType,
            'DNSupplierNo'=> $request->DNSupplierNo,
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

        return fractal()
	            ->item($sptrnprcvhdr)
	            ->transformWith(new SptrnprcvhdrTransformer)
	            ->toArray();
    }
}
