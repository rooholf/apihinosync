<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Svtrnservice extends Model
{
    protected $table = 'svTrnService';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'ProductType', 'ServiceNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'ProductType', 
        'ServiceNo', 
        'ServiceType', 
        'ServiceStatus', 
        'JobOrderNo', 
        'JobOrderDate', 
        'EstimationNo', 
        'EstimationDate', 
        'BookingNo', 
        'BookingDate', 
        'InvoiceNo', 
        'ForemanID', 
        'MechanicID', 
        'CustomerCode', 
        'CustomerCodeBill', 
        'PoliceRegNo', 
        'ServiceBookNo', 
        'BasicModel', 
        'TransmissionType', 
        'VIN', 
        'ChassisCode', 
        'ChassisNo', 
        'EngineCode', 
        'EngineNo', 
        'ColorCode', 
        'Odometer', 
        'JobType', 
        'ServiceRequestDesc', 
        'ConfirmChangingPart', 
        'EstimateFinishDate', 
        'EstimateFinishDateSys', 
        'LaborDiscPct', 
        'PartDiscPct', 
        'MaterialDiscAmt', 
        'InsurancePayFlag', 
        'InsuranceOwnRisk', 
        'InsuranceNo', 
        'InsuranceJobOrderNo', 
        'PPNPct', 
        'PPHPct', 
        'LaborGrossAmt', 
        'PartsGrossAmt', 
        'MaterialGrossAmt', 
        'LaborDiscAmt', 
        'PartsDiscAmt', 
        'MaterialDiscPct', 
        'LaborDppAmt', 
        'PartsDppAmt', 
        'MaterialDppAmt', 
        'TotalDPPAmount', 
        'TotalPphAmount',
        'TotalPpnAmount', 
        'TotalSrvAmount', 
        'PrintSeq', 
        'IsLocked', 
        'LockingBy', 
        'LockingDate', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate', 
        'IsSparepartClaim', 
        'JobOrderClosed',
        'InvDocNo',
    ];
}
