<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnprcvhdrdtl extends Model
{
    protected $table = 'spTrnPRcvDtl';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'WRSNo', 'PartNo', 'DocNo', 'BoxNo', 'LocationCode'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'WRSNo', 
        'PartNo', 
        'DocNo', 
        'DocDate', 
        'WarehouseCode', 
        'LocationCode', 
        'BoxNo', 
        'ReceivedQty', 
        'PurchasePrice', 
        'CostPrice', 
        'DiscPct', 
        'ABCClass', 
        'MovingCode', 
        'ProductType', 
        'PartCategory', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate',
    ];
}
