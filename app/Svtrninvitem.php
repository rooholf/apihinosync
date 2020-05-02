<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrninvitem extends Model
{
    protected $table = 'svTrnInvItem';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'InvoiceNo', 'PartNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'ProductType', 
        'InvoiceNo',
        'PartNo', 
        'MovingCode', 
        'ABCClass', 
        'SupplyQty', 
        'ReturnQty', 
        'CostPrice',
        'RetailPrice', 
        'TypeOfGoods', 
        'DiscPct',
        'MechanicID',
        'CreatedBy',
    ];
}
