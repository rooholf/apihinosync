<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spmstitem extends Model
	{
		protected $table = 'spMstItems';
		protected $primaryKey = ['CompanyCode','BranchCode','PartNo'];
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'BranchCode', 
			'PartNo', 
			'MovingCode', 
			'DemandAverage', 
			'BornDate', 
			'ABCClass', 
			'LastDemandDate', 
			'LastPurchaseDate', 
			'LastSalesDate', 
			'BOMInvAmt', 
			'BOMInvQty', 
			'BOMInvCostPrice',
			'OnOrder',
			'InTransit',
			'OnHand',
			'AllocationSP',
			'AllocationSR',
			'AllocationSL',
			'BackOrderSP',
			'BackOrderSR',
			'BackOrderSL',
			'ReservedSP',
			'ReservedSR',
			'ReservedSL',
			'BorrowQty',
			'BorrowedQty',
			'SalesUnit',
			'OrderUnit',
			'OrderPointQty',
			'SafetyStockQty',
			'LeadTime',
			'OrderCycle',
			'SafetyStock',
			'Utility1',
			'Utility2',
			'Utility3',
			'Utility4',
			'TypeOfGoods',
			'Status',
			'ProductType',
			'PartCategory',
			'CreatedBy',
			'CreatedDate',
			'LastUpdateBy',
			'LastUpdateDate',
			'isLocked',
			'LockingBy',
			'LockingDate',
			'PurcDiscPct',
		];
		
	}
