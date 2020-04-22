<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arbeginbalancehdr extends Model
{
    protected $table = 'arBeginBalanceHdr';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'DocNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'DocNo', 
        'ProfitCenterCode', 
        'DocDate', 
        'CustomerCode', 
        'AccountNo', 
        'DueDate', 
        'TOPCode', 
        'Amount', 
        'SalesCode', 
        'LeasingCode', 
        'Status', 
        'CreatedBy', 
        'CreatedDate', 
        'PrintSeq',

    ];
}
