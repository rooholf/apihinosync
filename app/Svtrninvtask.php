<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrninvtask extends Model
{
    protected $table = 'svTrnSrvTask';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'ServiceNo', 'OperationNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'ProductType', 
        'ServiceNo', 
        'OperationNo', 
        'OperationHour', 
        'OperationCost', 
        'IsSubCon', 
        'SubConPrice', 
        'PONo', 
        'ClaimHour', 
        'TypeOfGoods', 
        'BillType', 
        'SharingTask', 
        'TaskStatus', 
        'StartService', 
        'FinishService', 
        'CreatedBy', 
        'CreatedDate', 
        'LastupdateBy', 
        'LastupdateDate', 
        'DiscPct',
    ];
}
