<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrnsrvitem extends Model
{
    protected $table = 'svTrnSrvItem';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'ServiceNo', 'PartNo', 'PartSeq'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
		'CompanyCode', 
		'BranchCode', 
		'ProductType', 
		'ServiceNo',
		'PartNo', 
		'PartSeq', 
		'DemandQty', 
		'SupplyQty', 
		'ReturnQty', 
		'CostPrice',
		'RetailPrice', 
		'TypeOfGoods', 
		'BillType',
		'SupplySlipNo', 
		'SupplySlipDate', 
		'SSReturnNo',
		'SSReturnDate', 
		'CreatedBy', 
		'CreatedDate', 
		'LastupdateBy', 
		'LastupdateDate',
		'DiscPct', 
		'MechanicID',
		'AmountDiscount',
    ];
}
