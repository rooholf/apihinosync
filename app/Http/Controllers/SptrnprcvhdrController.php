<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use Illuminate\Http\Request;
use App\Sptrnprcvhdr;
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

    public function add(Request $request, Sptrnprcvhdr $sptrnprcvhdr)
    {
        $this->validate($request, [
            'WRSNo' => 'required',
        ]);

        $sptrnprcvhdr = $sptrnprcvhdr->firstOrCreate([
            'CompanyCode'=> $request->CompanyCode,
            'BranchCode'=> $request->BranchCode,
            'WRSNo'=> $request->WRSNo,
            'WRSDate'=> $request->WRSDate,
            'BinningNo'=> $request->BinningNo,
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
