<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spmstiteminfo extends Model
	{
		protected $table = 'spMstItemInfo';
		protected $primaryKey = 'CustomerCode';
		public $incrementing = false;
		
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