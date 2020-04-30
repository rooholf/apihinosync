<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrninvitemdtl extends Model
{
    protected $table = 'svTrnInvItemDtl';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'InvoiceNo', 'PartNo', 'SupplySlipNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'ProductType', 
        'InvoiceNo', 
        'PartNo', 
        'SupplySlipNo', 
        'SupplyQty', 
        'CostPrice', 
        'CreatedBy', 
        'CreatedDate',
    ];
}
