<?php

	namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB; // DB
	use Illuminate\Support\Str; //

	use App\Spmstitem;
	use App\Spmstiteminfo;
	use App\Spmstitemloc;
    use App\Spmstitemprice;
    use App\Transformers\SpmstitemTransformer; //transformer
	use Auth;

	use Carbon\Carbon;

	class SpmstitemController extends Controller
	{
		public function show(Request $request, Spmstitem $spmstitem)
		{
			$items = $spmstitem->where('PartNo', $request->PartNo)
								->where('CompanyCode', 'TUA00')
								->where('BranchCode', '000')->first();

			if ($items) {
				return fractal()
					->item($items)
					->transformWith(new SpmstitemTransformer)
					->toArray();
			} else {

				return response()->json([
                    'data' => 0
                ], 200);
			}
			// dd($customer);
				
		}

		public function showtwo(Request $request, Spmstitem $spmstitem)
		{
			$items = $spmstitem->where('PartNo', $request->PartNo)
								->where('CompanyCode', 'TUA00')
								->where('BranchCode', '002')->first();

			if ($items) {
				return fractal()
					->item($items)
					->transformWith(new SpmstitemTransformer)
					->toArray();
			} else {

				return response()->json([
                    'data' => 0
                ], 200);
			}
			// dd($customer);
				
		}

		public function add(Request $request, Spmstitem $spmstitem, Spmstiteminfo $spmstiteminfo, Spmstitemloc $spmstitemloc, Spmstitemprice $spmstitemprice)
		{
			$this->validate($request, [
            	'PartNo' => 'required',
			]);

			$spmstitem = $spmstitem->create([
		        'CompanyCode' => $request->CompanyCode,
				'BranchCode' => $request->BranchCode,
				'PartNo' => $request->PartNo,
				'MovingCode' => $request->MovingCode,
				'DemandAverage' => $request->DemandAverage,
				'BornDate' => $request->BornDate,
				'ABCClass' => $request->ABCClass,
				'LastDemandDate' => $request->LastDemandDate,
				'LastPurchaseDate' => $request->LastPurchaseDate,
				'LastSalesDate' => $request->LastSalesDate,
				'BOMInvAmt' => $request->BOMInvAmt,
				'BOMInvQty' => $request->BOMInvQty,
				'BOMInvCostPrice' => $request->BOMInvCostPrice,
				'OnOrder' => $request->OnOrder,
				'InTransit' => $request->InTransit,
				'OnHand' => $request->OnHand,
				'AllocationSP' => $request->AllocationSP,
				'AllocationSR' => $request->AllocationSR,
				'AllocationSL' => $request->AllocationSL,
				'BackOrderSP' => $request->BackOrderSP,
				'BackOrderSR' => $request->BackOrderSR,
				'BackOrderSL' => $request->BackOrderSL,
				'ReservedSP' => $request->ReservedSP,
				'ReservedSR' => $request->ReservedSR,
				'ReservedSL' => $request->ReservedSL,
				'BorrowQty' => $request->BorrowQty,
				'BorrowedQty' => $request->BorrowedQty,
				'SalesUnit' => $request->SalesUnit,
				'OrderUnit' => $request->OrderUnit,
				'OrderPointQty' => $request->OrderPointQty,
				'SafetyStockQty' => $request->SafetyStockQty,
				'LeadTime' => $request->LeadTime,
				'OrderCycle' => $request->OrderCycle,
				'SafetyStock' => $request->SafetyStock,
				'Utility1' => $request->Utility1,
				'Utility2' => $request->Utility2,
				'Utility3' => $request->Utility3,
				'Utility4' => $request->Utility4,
				'TypeOfGoods' => $request->TypeOfGoods,
				'Status' => $request->Status,
				'ProductType' => $request->ProductType,
				'PartCategory' => $request->PartCategory,
				'CreatedBy' => $request->CreatedBy,
				'CreatedDate' => Carbon::now(),
				'LastUpdateBy' => $request->LastUpdateBy,
				'LastUpdateDate' => Carbon::now(),
				'isLocked' => $request->isLocked,
				'LockingBy' => $request->LockingBy,
				'LockingDate' => Carbon::now(),
				'PurcDiscPct' => $request->PurcDiscPct,
            ]);

            $iteminfo = $spmstiteminfo->where('CompanyCode', $request->CompanyCode)
            						->where('PartNo', $request->PartNo)
            						->where('SupplierCode', $request->SupplierCode);

            if ($iteminfo->count() < 1) {
            	$spmstiteminfo = $spmstiteminfo->create([
	                'CompanyCode'=> $request->CompanyCode,
	                'PartNo'=> $request->PartNo,
	                'SupplierCode'=> $request->SupplierCode,
	                'PartName'=> $request->PartName,
	                'IsGenuinePart'=> $request->IsGenuinePart,
	                'DiscPct'=> $request->DiscPct,
	                'SalesUnit'=> $request->SalesUnit,
	                'OrderUnit'=> $request->OrderUnit,
	                'PurchasePrice'=> $request->PurchasePrice,
	                'UOMCode'=> $request->UOMCode,
	                'Status'=> $request->Status,
	                'ProductType'=> $request->ProductType,
	                'PartCategory'=> $request->PartCategory,
	                'CreatedBy'=> $request->CreatedBy,
	                'CreatedDate'=> Carbon::now(),
	                'LastUpdateBy'=> $request->LastUpdateBy,
	                'LastUpdateDate'=> Carbon::now(),
	                'isLocked'=> $request->isLocked,
	                'LockingBy'=> $request->LockingBy,
	                'LockingDate'=> Carbon::now(),
	            ]);
            }

            $itemloc = $spmstitemloc->where('CompanyCode', $request->CompanyCode)
            						->where('PartNo', $request->PartNo)
            						->where('BranchCode', $request->BranchCode);

           	if ($itemloc->count() < 1) {
           		$spmstitemloc = $spmstitemloc->create([
	                'CompanyCode'=> $request->CompanyCode,
	                'BranchCode'=> $request->BranchCode,
	                'PartNo'=> $request->PartNo,
	                'WarehouseCode'=> $request->WarehouseCode,
	                'LocationCode'=> $request->LocationCode,
	                'LocationSub1'=> $request->LocationSub1,
	                'LocationSub2'=> $request->LocationSub2,
	                'LocationSub3'=> $request->LocationSub3,
	                'LocationSub4'=> $request->LocationSub4,
	                'LocationSub5'=> $request->LocationSub5,
	                'LocationSub6'=> $request->LocationSub6,
	                'BOMInvAmount'=> $request->BOMInvAmount,
	                'BOMInvQty'=> $request->BOMInvQty,
	                'BOMInvCostPrice'=> $request->BOMInvCostPrice,
	                'OnHand'=> $request->OnHand,
	                'AllocationSP'=> $request->AllocationSP,
	                'AllocationSR'=> $request->AllocationSR,
	                'AllocationSL'=> $request->AllocationSL,
	                'BackOrderSP'=> $request->BackOrderSP,
	                'BackOrderSR'=> $request->BackOrderSR,
	                'BackOrderSL'=> $request->BackOrderSL,
	                'ReservedSP'=> $request->ReservedSP,
	                'ReservedSR'=> $request->ReservedSR,
	                'ReservedSL'=> $request->ReservedSL,
	                'Status'=> $request->Status,
	                'CreatedBy'=> $request->CreatedBy,
	                'CreatedDate'=> Carbon::now(),
	                'LastUpdateBy'=> $request->LastUpdateBy,
	                'LastUpdateDate'=> Carbon::now(),
	                'isLocked'=> $request->isLocked,
	                'LockingBy'=> $request->LockingBy,
	                'LockingDate'=> Carbon::now(),
	            ]);
           	}

	            

            $RetailPriceInclTax = $request->RetailPrice * 1.1;

            $itemprice = $spmstitemprice->where('CompanyCode', $request->CompanyCode)
            						->where('PartNo', $request->PartNo)
            						->where('BranchCode', $request->BranchCode);

            if ($itemprice->count() < 1) {
            	$spmstitemprice = $spmstitemprice->create([
			        'CompanyCode' => $request->CompanyCode,
					'BranchCode' => $request->BranchCode,
					'PartNo' => $request->PartNo,
					'RetailPrice' => $request->RetailPrice,
					'RetailPriceInclTax' => $RetailPriceInclTax,
					'PurchasePrice' => $request->PurchasePrice,
					'CostPrice' => $RetailPriceInclTax,
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
            }
				

			return fractal()
                ->item($spmstitem)
                ->transformWith(new SpmstitemTransformer)
                ->toArray();

		}

		public function update(Request $request, Spmstitem $spmstitem)
		{
			$spmstitem->CompanyCode = $request->get('CompanyCode', $spmstitem->CompanyCode);
			$spmstitem->BranchCode = $request->get('BranchCode', $spmstitem->BranchCode);
			$spmstitem->PartNo = $request->get('PartNo', $spmstitem->PartNo);
			$spmstitem->MovingCode = $request->get('MovingCode', $spmstitem->MovingCode);
			$spmstitem->DemandAverage = $request->get('DemandAverage', $spmstitem->DemandAverage);
			$spmstitem->BornDate = $request->get('BornDate', $spmstitem->BornDate);
			$spmstitem->ABCClass = $request->get('ABCClass', $spmstitem->ABCClass);
			$spmstitem->LastDemandDate = $request->get('LastDemandDate', $spmstitem->LastDemandDate);
			$spmstitem->LastPurchaseDate = $request->get('LastPurchaseDate', $spmstitem->LastPurchaseDate);
			$spmstitem->LastSalesDate = $request->get('LastSalesDate', $spmstitem->LastSalesDate);
			$spmstitem->BOMInvAmt = $request->get('BOMInvAmt', $spmstitem->BOMInvAmt);
			$spmstitem->BOMInvQty = $request->get('BOMInvQty', $spmstitem->BOMInvQty);
			$spmstitem->BOMInvCostPrice = $request->get('BOMInvCostPrice', $spmstitem->BOMInvCostPrice);
			$spmstitem->OnOrder = $request->get('OnOrder', $spmstitem->OnOrder);
			$spmstitem->InTransit = $request->get('InTransit', $spmstitem->InTransit);
			$spmstitem->OnHand = $request->get('OnHand', $spmstitem->OnHand);
			$spmstitem->AllocationSP = $request->get('AllocationSP', $spmstitem->AllocationSP);
			$spmstitem->AllocationSR = $request->get('AllocationSR', $spmstitem->AllocationSR);
			$spmstitem->AllocationSL = $request->get('AllocationSL', $spmstitem->AllocationSL);
			$spmstitem->BackOrderSP = $request->get('BackOrderSP', $spmstitem->BackOrderSP);
			$spmstitem->BackOrderSR = $request->get('BackOrderSR', $spmstitem->BackOrderSR);
			$spmstitem->BackOrderSL = $request->get('BackOrderSL', $spmstitem->BackOrderSL);
			$spmstitem->ReservedSP = $request->get('ReservedSP', $spmstitem->ReservedSP);
			$spmstitem->ReservedSR = $request->get('ReservedSR', $spmstitem->ReservedSR);
			$spmstitem->ReservedSL = $request->get('ReservedSL', $spmstitem->ReservedSL);
			$spmstitem->BorrowQty = $request->get('BorrowQty', $spmstitem->BorrowQty);
			$spmstitem->BorrowedQty = $request->get('BorrowedQty', $spmstitem->BorrowedQty);
			$spmstitem->SalesUnit = $request->get('SalesUnit', $spmstitem->SalesUnit);
			$spmstitem->OrderUnit = $request->get('OrderUnit', $spmstitem->OrderUnit);
			$spmstitem->OrderPointQty = $request->get('OrderPointQty', $spmstitem->OrderPointQty);
			$spmstitem->SafetyStockQty = $request->get('SafetyStockQty', $spmstitem->SafetyStockQty);
			$spmstitem->LeadTime = $request->get('LeadTime', $spmstitem->LeadTime);
			$spmstitem->OrderCycle = $request->get('OrderCycle', $spmstitem->OrderCycle);
			$spmstitem->SafetyStock = $request->get('SafetyStock', $spmstitem->SafetyStock);
			$spmstitem->Utility1 = $request->get('Utility1', $spmstitem->Utility1);
			$spmstitem->Utility2 = $request->get('Utility2', $spmstitem->Utility2);
			$spmstitem->Utility3 = $request->get('Utility3', $spmstitem->Utility3);
			$spmstitem->Utility4 = $request->get('Utility4', $spmstitem->Utility4);
			$spmstitem->TypeOfGoods = $request->get('TypeOfGoods', $spmstitem->TypeOfGoods);
			$spmstitem->Status = $request->get('Status', $spmstitem->Status);
			$spmstitem->ProductType = $request->get('ProductType', $spmstitem->ProductType);
			$spmstitem->PartCategory = $request->get('PartCategory', $spmstitem->PartCategory);
			$spmstitem->CreatedBy = $request->get('CreatedBy', $spmstitem->CreatedBy);
			$spmstitem->CreatedDate = $request->get('CreatedDate', $spmstitem->CreatedDate);
			$spmstitem->LastUpdateBy = $request->get('LastUpdateBy', $spmstitem->LastUpdateBy);
			$spmstitem->LastUpdateDate = $request->get('LastUpdateDate', $spmstitem->LastUpdateDate);
			$spmstitem->isLocked = $request->get('isLocked', $spmstitem->isLocked);
			$spmstitem->LockingBy = $request->get('LockingBy', $spmstitem->LockingBy);
			$spmstitem->LockingDate = $request->get('LockingDate', $spmstitem->LockingDate);
			$spmstitem->PurcDiscPct = $request->get('PurcDiscPct', $spmstitem->PurcDiscPct);
			$spmstitem->save();


			$spmstiteminfo->CompanyCode = $request->get('CompanyCode', $spmstiteminfo->CompanyCode);
			$spmstiteminfo->PartNo = $request->get('PartNo', $spmstiteminfo->PartNo);
			$spmstiteminfo->SupplierCode = $request->get('SupplierCode', $spmstiteminfo->SupplierCode);
			$spmstiteminfo->PartName = $request->get('PartName', $spmstiteminfo->PartName);
			$spmstiteminfo->IsGenuinePart = $request->get('IsGenuinePart', $spmstiteminfo->IsGenuinePart);
			$spmstiteminfo->DiscPct = $request->get('DiscPct', $spmstiteminfo->DiscPct);
			$spmstiteminfo->SalesUnit = $request->get('SalesUnit', $spmstiteminfo->SalesUnit);
			$spmstiteminfo->OrderUnit = $request->get('OrderUnit', $spmstiteminfo->OrderUnit);
			$spmstiteminfo->PurchasePrice = $request->get('PurchasePrice', $spmstiteminfo->PurchasePrice);
			$spmstiteminfo->UOMCode = $request->get('UOMCode', $spmstiteminfo->UOMCode);
			$spmstiteminfo->Status = $request->get('Status', $spmstiteminfo->Status);
			$spmstiteminfo->ProductType = $request->get('ProductType', $spmstiteminfo->ProductType);
			$spmstiteminfo->PartCategory = $request->get('PartCategory', $spmstiteminfo->PartCategory);
			$spmstiteminfo->CreatedBy = $request->get('CreatedBy', $spmstiteminfo->CreatedBy);
			$spmstiteminfo->CreatedDate = $request->get('CreatedDate', $spmstiteminfo->CreatedDate);
			$spmstiteminfo->LastUpdateBy = $request->get('LastUpdateBy', $spmstiteminfo->LastUpdateBy);
			$spmstiteminfo->LastUpdateDate = $request->get('LastUpdateDate', $spmstiteminfo->LastUpdateDate);
			$spmstiteminfo->isLocked = $request->get('isLocked', $spmstiteminfo->isLocked);
			$spmstiteminfo->LockingBy = $request->get('LockingBy', $spmstiteminfo->LockingBy);
			$spmstiteminfo->LockingDate = $request->get('LockingDate', $spmstiteminfo->LockingDate);
			$spmstiteminfo->save();

			$spmstitemloc->CompanyCode= $request->get('CompanyCode', $spmstitemloc->CompanyCode);
			$spmstitemloc->BranchCode= $request->get('BranchCode', $spmstitemloc->BranchCode);
			$spmstitemloc->PartNo= $request->get('PartNo', $spmstitemloc->PartNo);
			$spmstitemloc->WarehouseCode= $request->get('WarehouseCode', $spmstitemloc->WarehouseCode);
			$spmstitemloc->LocationCode= $request->get('LocationCode', $spmstitemloc->LocationCode);
			$spmstitemloc->LocationSub1= $request->get('LocationSub1', $spmstitemloc->LocationSub1);
			$spmstitemloc->LocationSub2= $request->get('LocationSub2', $spmstitemloc->LocationSub2);
			$spmstitemloc->LocationSub3= $request->get('LocationSub3', $spmstitemloc->LocationSub3);
			$spmstitemloc->LocationSub4= $request->get('LocationSub4', $spmstitemloc->LocationSub4);
			$spmstitemloc->LocationSub5= $request->get('LocationSub5', $spmstitemloc->LocationSub5);
			$spmstitemloc->LocationSub6= $request->get('LocationSub6', $spmstitemloc->LocationSub6);
			$spmstitemloc->BOMInvAmount= $request->get('BOMInvAmount', $spmstitemloc->BOMInvAmount);
			$spmstitemloc->BOMInvQty= $request->get('BOMInvQty', $spmstitemloc->BOMInvQty);
			$spmstitemloc->BOMInvCostPrice= $request->get('BOMInvCostPrice', $spmstitemloc->BOMInvCostPrice);
			$spmstitemloc->OnHand= $request->get('OnHand', $spmstitemloc->OnHand);
			$spmstitemloc->AllocationSP= $request->get('AllocationSP', $spmstitemloc->AllocationSP);
			$spmstitemloc->AllocationSR= $request->get('AllocationSR', $spmstitemloc->AllocationSR);
			$spmstitemloc->AllocationSL= $request->get('AllocationSL', $spmstitemloc->AllocationSL);
			$spmstitemloc->BackOrderSP= $request->get('BackOrderSP', $spmstitemloc->BackOrderSP);
			$spmstitemloc->BackOrderSR= $request->get('BackOrderSR', $spmstitemloc->BackOrderSR);
			$spmstitemloc->BackOrderSL= $request->get('BackOrderSL', $spmstitemloc->BackOrderSL);
			$spmstitemloc->ReservedSP= $request->get('ReservedSP', $spmstitemloc->ReservedSP);
			$spmstitemloc->ReservedSR= $request->get('ReservedSR', $spmstitemloc->ReservedSR);
			$spmstitemloc->ReservedSL= $request->get('ReservedSL', $spmstitemloc->ReservedSL);
			$spmstitemloc->Status= $request->get('Status', $spmstitemloc->Status);
			$spmstitemloc->CreatedBy= $request->get('CreatedBy', $spmstitemloc->CreatedBy);
			$spmstitemloc->CreatedDate= $request->get('CreatedDate', $spmstitemloc->CreatedDate);
			$spmstitemloc->LastUpdateBy= $request->get('LastUpdateBy', $spmstitemloc->LastUpdateBy);
			$spmstitemloc->LastUpdateDate= $request->get('LastUpdateDate', $spmstitemloc->LastUpdateDate);
			$spmstitemloc->isLocked= $request->get('isLocked', $spmstitemloc->isLocked);
			$spmstitemloc->LockingBy= $request->get('LockingBy', $spmstitemloc->LockingBy);
			$spmstitemloc->LockingDate= $request->get('LockingDate', $spmstitemloc->LockingDate);
			$spmstitemloc->save();

			$spmstitemprice->CompanyCode= $request->get('CompanyCode', $spmstitemprice->CompanyCode);
			$spmstitemprice->BranchCode= $request->get('BranchCode', $spmstitemprice->BranchCode);
			$spmstitemprice->PartNo= $request->get('PartNo', $spmstitemprice->PartNo);
			$spmstitemprice->RetailPrice= $request->get('RetailPrice', $spmstitemprice->RetailPrice);
			$spmstitemprice->RetailPriceInclTax= $request->get('RetailPriceInclTax', $spmstitemprice->RetailPriceInclTax);
			$spmstitemprice->PurchasePrice= $request->get('PurchasePrice', $spmstitemprice->PurchasePrice);
			$spmstitemprice->CostPrice= $request->get('CostPrice', $spmstitemprice->CostPrice);
			$spmstitemprice->OldRetailPrice= $request->get('OldRetailPrice', $spmstitemprice->OldRetailPrice);
			$spmstitemprice->OldPurchasePrice= $request->get('OldPurchasePrice', $spmstitemprice->OldPurchasePrice);
			$spmstitemprice->OldCostPrice= $request->get('OldCostPrice', $spmstitemprice->OldCostPrice);
			$spmstitemprice->LastPurchaseUpdate= $request->get('LastPurchaseUpdate', $spmstitemprice->LastPurchaseUpdate);
			$spmstitemprice->LastRetailPriceUpdate= $request->get('LastRetailPriceUpdate', $spmstitemprice->LastRetailPriceUpdate);
			$spmstitemprice->CreatedBy= $request->get('CreatedBy', $spmstitemprice->CreatedBy);
			$spmstitemprice->CreatedDate= $request->get('CreatedDate', $spmstitemprice->CreatedDate);
			$spmstitemprice->LastUpdateBy= $request->get('LastUpdateBy', $spmstitemprice->LastUpdateBy);
			$spmstitemprice->LastUpdateDate= $request->get('LastUpdateDate', $spmstitemprice->LastUpdateDate);
			$spmstitemprice->isLocked= $request->get('isLocked', $spmstitemprice->isLocked);
			$spmstitemprice->LockingBy= $request->get('LockingBy', $spmstitemprice->LockingBy);
			$spmstitemprice->LockingDate= $request->get('LockingDate', $spmstitemprice->LockingDate);
			$spmstitemprice->save();

			return fractal()
                ->item($gnmstcustomer)
                ->transformWith(new SupplierTransformer)
                ->toArray();
		}
	}
