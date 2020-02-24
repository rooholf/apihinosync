<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstcustomerprofitcenter extends Model
	{
		protected $table = 'gnMstCustomerProfitCenter';
		protected $primaryKey = 'CustomerCode';
		public $incrementing = false;
		
		protected $fillable = [
        'CompanyCode', 
		'BranchCode', 
		'CustomerCode', 
		'ProfitCenterCode', 
		'CreditLimit', 
		'PaymentCode', 
		'CustomerClass', 
		'TaxCode', 
		'TaxTransCode', 
		'DiscPct', 
		'LaborDiscPct', 
		'PartDiscPct', 
		'MaterialDiscPct',
		'TOPCode',
		'CustomerGrade',
		'ContactPerson',
		'CollectorCode',
		'GroupPriceCode',
		'isOverDueAllowed',
		'SalesCode',
		'SalesType',
		'Salesman',
		'isBlackList',
		'CreatedBy',
		'CreatedDate',
		'LastUpdateBy',
		'LastUpdateDate',
		'isLocked',
		'LockingBy',
		'LockingDate',
		];
		
	}