<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apbeginbalancehdr extends Model
{
    protected $table = 'apBeginBalanceHdr';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'DocNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
    	'CompanyCode', 
    	'BranchCode', 
    	'DocNo', 
    	'ProfitCenterCode', 
    	'DocDate', 
    	'SupplierCode', 
    	'AccountNo', 
    	'DueDate', 
    	'TOPCode', 
    	'Amount', 
    	'Status', 
    	'CreatedBy', 
    	'CreatedDate', 
    	'PrintSeq',

    ];

}
