<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrninvtask extends Model
{
    protected $table = 'svTrnInvTask';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'InvoiceNo', 'OperationNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'ProductType', 
        'InvoiceNo',
        'OperationNo', 
        'OperationHour', 
        'ClaimHour', 
        'OperationCost', 
        'SubConPrice',
        'IsSubCon', 
        'SharingTask',
        'DiscPct',
        'CreatedBy',
    ];
}
