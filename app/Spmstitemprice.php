<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spmstitemprice extends Model
	{
		protected $table = 'spMstItemPrice';
		protected $primaryKey = ['CompanyCode', 'BranchCode', 'PartNo'];
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'BranchCode', 
			'PartNo', 
			'RetailPrice', 
			'RetailPriceInclTax', 
			'PurchasePrice', 
			'CostPrice', 
			'OldRetailPrice', 
			'OldPurchasePrice', 
			'OldCostPrice', 
			'LastPurchaseUpdate', 
			'LastRetailPriceUpdate', 
			'CreatedBy',
			'CreatedDate',
			'LastUpdateBy',
			'LastUpdateDate',
			'isLocked',
			'LockingBy',
			'LockingDate',
		];
		
	}
