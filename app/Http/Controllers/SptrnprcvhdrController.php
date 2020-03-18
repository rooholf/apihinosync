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
            'CompanyCode' => $request->CompanyCode,
			'BranchCode' => $request->BranchCode,
			'PartNo' => $request->PartNo,
			'RetailPrice' => $request->RetailPrice,
			'RetailPriceInclTax' => $request->RetailPriceInclTax,
			'PurchasePrice' => $request->PurchasePrice,
			'CostPrice' => $request->CostPrice,
			'OldRetailPrice' => $request->OldRetailPrice,
			'OldPurchasePrice' => $request->OldPurchasePrice,
			'OldCostPrice' => $request->OldCostPrice,
			'LastPurchaseUpdate' => $request->LastPurchaseUpdate,
			'LastRetailPriceUpdate' => $request->LastRetailPriceUpdate,
			'CreatedBy' => $request->CreatedBy,
			'CreatedDate' => Carbon::now(),
			'LastUpdateBy' => $request->LastUpdateBy,
			'LastUpdateDate' => Carbon::now(),
			'isLocked' => $request->isLocked,
			'LockingBy' => $request->LockingBy,
			'LockingDate' => Carbon::now(),
        ]);

        return fractal()
	            ->item($sptrnprcvhdr)
	            ->transformWith(new SptrnprcvhdrTransformer)
	            ->toArray();
    }
}
