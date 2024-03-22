<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApInterface extends Model
{
    protected $table = 'apInterface';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'DocNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'DocNo', 
        'ProfitCenterCode', 
        'DocDate', 
	'Reference', 
	'ReferenceDate',
	'NetAmt', 
	'PPHAmt', 
	'SupplierCode', 
	'PPNAmt', 
	'PPnBM', 
	'AccountNo', 
        'TermsDate', 
	'TermsName', 
	'TotalAmt', 
	'StatusFlag', 
	'CreateBy', 
	'CreateDate', 
	'ReceiveAmt', 
	'FakturPajakNo', 
	'FakturPajakDate', 
	'RefNo',
    ];
}
