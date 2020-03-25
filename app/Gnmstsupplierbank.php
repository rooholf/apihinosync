<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstsupplierbank extends Model
	{
		protected $table = 'gnMstSupplierBank';
		protected $primaryKey = 'SupplierCode';
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'SupplierCode', 
			'BankCode', 
			'BankName', 
			'AccountName', 
			'AccountBank', 
			'CreatedBy', 
			'CreatedDate', 
			'LastUpdateBy', 
			'LastUpdateDate', 
			'isLocked', 
			'LockingBy', 
			'LockingDate',
		];
		
	}
