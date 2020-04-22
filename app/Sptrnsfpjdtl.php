<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnsfpjdtl extends Model
{
    protected $table = 'spTrnSFPJDtl';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'FPJNo', 'WarehouseCode', 'PartNo', 'PartNoOriginal', 'DocNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'FPJNo', 
        'WarehouseCode', 
        'PartNo', 
        'PartNoOriginal', 
        'DocNo', 
        'DocDate', 
        'ReferenceNo', 
        'ReferenceDate', 
        'LocationCode', 
        'QtyBill', 
        'RetailPriceInclTax', 
        'RetailPrice', 
        'CostPrice', 
        'DiscPct', 
        'SalesAmt', 
        'DiscAmt', 
        'NetSalesAmt', 
        'PPNAmt', 
        'TotSalesAmt', 
        'ProductType', 
        'PartCategory', 
        'MovingCode', 
        'ABCClass', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate',
    ];
}
