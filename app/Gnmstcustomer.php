<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstcustomer extends Model
{
    protected $table = 'gnMstCustomer';
    protected $primaryKey = 'CustomerCode';
    public $incrementing = false;

    protected $fillable = [
        'CompanyCode', 'CustomerCode', 'StandardCode', 'CustomerName', 'CustomerAbbrName', 'CustomerGovName', 'CustomerType', 'CategoryCode', 'Address1', 'Address2', 'Address3', 'Address4', 'PhoneNo', 'HPNo', 'FaxNo', 'isPKP', 'NPWPNo', 'NPWPDate', 'SKPNo', 'SKPDate', 'ProvinceCode', 'AreaCode', 'CityCode', 'ZipNo', 'Status', 'CreatedBy', 'CreatedDate', 'LastUpdateBy', 'LastUpdateDate', 'isLocked', 'LockingBy', 'LockingDate', 'Email', 'BirthDate', 'Spare01', 'Spare02', 'Spare03', 'Spare04', 'Spare05', 'Gender', 'OfficePhoneNo', 'KelurahanDesa', 'KecamatanDistrik', 'KotaKabupaten', 'IbuKota', 'CustomerStatus',
    ];

    protected $hidden = [
        'CompanyCode',
    ];

}
