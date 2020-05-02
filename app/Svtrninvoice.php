<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrninvoice extends Model
{
    protected $table = 'svTrnInvoice';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'InvoiceNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'ProductType', 
        'InvoiceNo', 
        'InvoiceDate', 
        'InvoiceStatus', 
        'FPJNo', 
        'FPJDate', 
        'JobOrderNo', 
        'JobOrderDate', 
        'JobType', 
        'ServiceRequestDesc', 
        'ChassisCode', 
        'ChassisNo', 
        'EngineCode', 
        'EngineNo', 
        'PoliceRegNo', 
        'BasicModel', 
        'CustomerCode', 
        'CustomerCodeBill', 
        'Odometer', 
        'IsPKP', 
        'TOPCode', 
        'TOPDays', 
        'DueDate', 
        'SignedDate', 
        'LaborDiscPct', 
        'PartsDiscPct', 
        'MaterialDiscPct', 
        'PphPct', 
        'PpnPct', 
        'LaborGrossAmt', 
        'PartsGrossAmt', 
        'MaterialGrossAmt', 
        'LaborDiscAmt', 
        'PartsDiscAmt', 
        'MaterialDiscAmt', 
        'LaborDppAmt', 
        'PartsDppAmt', 
        'MaterialDppAmt', 
        'TotalDppAmt', 
        'TotalPphAmt', 
        'TotalPpnAmt', 
        'TotalSrvAmt', 
        'Remarks', 
        'PrintSeq', 
        'PostingFlag', 
        'PostingDate', 
        'IsLocked', 
        'LockingBy', 
        'LockingDate', 
        'CreatedBy', 
        'CreatedDate', 
        'LastupdateBy', 
        'LastupdateDate',
        'InvDocNo',
    ];
}
