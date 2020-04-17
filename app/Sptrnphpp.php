<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnphpp extends Model
{
    protected $table = 'spTrnPHPP';
	protected $primaryKey = ['CompanyCode', 'BranchCode', 'HPPNo'];
	public $incrementing = false;
	public $timestamps = false;
	
	protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'HPPNo', 
        'HPPDate', 
        'WRSNo', 
        'WRSDate', 
        'ReferenceNo', 
        'ReferenceDate', 
        'TotPurchAmt', 
        'TotNetPurchAmt', 
        'TotTaxAmt', 
        'TaxNo', 
        'TaxDate', 
        'MonthTax', 
        'YearTax', 
        'DueDate', 
        'DiffNetPurchAmt', 
        'DiffTaxAmt', 
        'TotHPPAmt', 
        'CostPrice', 
        'PrintSeq', 
        'TypeOfGoods', 
        'Status', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate'

	];

}
