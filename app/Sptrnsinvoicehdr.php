<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnsinvoicehdr extends Model
{
    protected $table = 'spTrnSInvoiceHdr';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'InvoiceNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'InvoiceNo', 
        'InvoiceDate', 
        'PickingSlipNo', 
        'PickingSlipDate', 
        'FPJNo', 
        'FPJDate', 
        'TransType', 
        'SalesType', 
        'CustomerCode', 
        'CustomerCodeBill', 
        'CustomerCodeShip', 
        'TotSalesQty', 
        'TotSalesAmt', 
        'TotDiscAmt', 
        'TotDPPAmt', 
        'TotPPNAmt', 
        'TotFinalSalesAmt', 
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
        'InvNo',
        'DocNo',
    ];
}
