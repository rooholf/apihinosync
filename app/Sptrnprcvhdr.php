<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnprcvhdr extends Model
{
    protected $table = 'spTrnPRcvHdr';
    protected $primaryKey = 'WRSNo';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'WRSNo', 
        'WRSDate', 
        'BinningNo', 
        'BinningDate', 
        'ReceivingType', 
        'DNSupplierNo', 
        'DNSupplierDate', 
        'TransType', 
        'SupplierCode', 
        'ReferenceNo', 
        'ReferenceDate', 
        'TotItem', 
        'TotWRSAmt', 
        'Status', 
        'PrintSeq', 
        'TypeOfGoods', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate', 
        'isLocked', 
        'LockingBy', 
        'LockingDate',
        'GRNo',
    ];
}
