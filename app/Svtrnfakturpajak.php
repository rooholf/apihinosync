<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrnfakturpajak extends Model
{
    protected $table = 'svTrnFakturPajak';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'FPJNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'FPJNo', 
        'FPJDate', 
        'FPJGovNo', 
        'FPJCentralNo', 
        'FPJCentralDate', 
        'NoOfInvoice', 
        'CustomerCode', 
        'CustomerCodeBill', 
        'CustomerName', 
        'Address1', 
        'Address2', 
        'Address3', 
        'Address4', 
        'PhoneNo', 
        'HPNo', 
        'IsPKP', 
        'SKPNo', 
        'SKPDate', 
        'NPWPNo', 
        'NPWPDate', 
        'TOPCode', 
        'TOPDays', 
        'DueDate', 
        'SignedDate', 
        'PrintSeq', 
        'GenerateStatus', 
        'IsLocked', 
        'LockingBy', 
        'LockingDate', 
        'CreatedBy', 
        'CreatedDate', 
        'LastupdateBy', 
        'LastupdateDate',
    ];
}
