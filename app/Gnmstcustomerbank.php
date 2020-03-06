<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;

	class Gnmstcustomerbank extends Model
	{
		protected $table = 'gnMstCustomerBank';
		protected $primaryKey = 'CustomerCode';
		public $incrementing = false;
		public $timestamps = false;
		
		protected $fillable = [
        	'CompanyCode', 'CustomerCode', 'BankCode', 'BankName', 'AccountName', 'AccountBank', 'CreatedBy', 'CreatedDate', 'LastUpdateBy', 'LastUpdateDate', 'isLocked', 'LockingBy', 'LockingDate',
		];
		
	}