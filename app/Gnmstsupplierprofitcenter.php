<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstsupplierprofitcenter extends Model
	{
		protected $table = 'gnMstSupplierProfitCenter';
		protected $primaryKey = ['CompanyCode','BranchCode','SupplierCode','ProfitCenterCode'];
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'BranchCode', 
			'SupplierCode', 
			'ProfitCenterCode', 
			'ContactPerson', 
			'SupplierClass', 
			'SupplierGrade', 
			'DiscPct', 
			'TOPCode', 
			'TaxCode', 
			'isBlackList', 
			'Status', 
			'CreatedBy',
			'CreatedDate',
			'LastUpdateBy',
			'LastUpdateDate',
		];
		
	}
