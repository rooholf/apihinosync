<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spmstiteminfo extends Model
	{
		protected $table = 'spMstItemInfo';
		protected $primaryKey = ['CompanyCode', 'PartNo'];
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'PartNo', 
			'SupplierCode', 
			'PartName', 
			'IsGenuinePart', 
			'DiscPct', 
			'SalesUnit', 
			'OrderUnit', 
			'PurchasePrice', 
			'UOMCode', 
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
		];
		
	}