<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnsfpjhdr extends Model
{
    protected $table = 'spTrnSFPJHdr';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'FPJNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'FPJNo', 
        'FPJDate', 
        'TPTrans', 
        'FPJGovNo', 
        'FPJSignature', 
        'FPJCentralNo', 
        'FPJCentralDate', 
        'DeliveryNo', 
        'InvoiceNo', 
        'InvoiceDate', 
        'PickingSlipNo', 
        'PickingSlipDate', 
        'TransType', 
        'CustomerCode', 
        'CustomerCodeBill', 
        'CustomerCodeShip', 
        'TOPCode', 
        'TOPDays', 
        'DueDate', 
        'TotSalesQty', 
        'TotSalesAmt', 
        'TotDiscAmt', 
        'TotDPPAmt', 
        'TotPPNAmt', 
        'TotFinalSalesAmt', 
        'isPKP', 
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
    ];
}
