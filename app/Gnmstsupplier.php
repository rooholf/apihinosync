<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstsupplier extends Model
	{
		protected $table = 'gnMstSupplier';
		protected $primaryKey = 'SupplierCode';
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
	        'CompanyCode', 
			'SupplierCode', 
			'StandardCode', 
			'SupplierName', 
			'SupplierGovName', 
			'Address1', 
			'Address2', 
			'Address3', 
			'Address4', 
			'PhoneNo', 
			'HPNo', 
			'FaxNo', 
			'ProvinceCode',
			'AreaCode',
			'CityCode',
			'ZipNo',
			'isPKP',
			'NPWPNo',
			'NPWPDate',
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