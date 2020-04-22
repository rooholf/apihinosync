<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnsinvoicedtl extends Model
{
    protected $table = 'spTrnSInvoiceDtl';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'InvoiceNo', 'WarehouseCode', 'PartNo', 'PartNoOriginal', 'DocNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'InvoiceNo', 
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
        'ExPickingListNo', 
        'ExPickingListDate', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate',
    ];
}
