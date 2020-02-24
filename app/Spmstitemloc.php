<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spmstitemloc extends Model
	{
		protected $table = 'spMstItemLoc';
		protected $primaryKey = 'CustomerCode';
		public $incrementing = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'BranchCode', 
			'PartNo', 
			'WarehouseCode', 
			'LocationCode', 
			'LocationSub1', 
			'LocationSub2', 
			'LocationSub3', 
			'LocationSub4', 
			'LocationSub5', 
			'LocationSub6', 
			'BOMInvAmount', 
			'BOMInvQty',
			'BOMInvCostPrice',
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
			'Status',
			'CreatedBy',
			'CreatedDate',
			'LastUpdateBy',
			'LastUpdateDate',
			'isLocked',
			'LockingBy',
			'LockingDate',
		];
		
	}
