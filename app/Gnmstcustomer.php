<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstcustomer extends Model
{
	// const CREATED_AT = 'CreatedDate';
 //    const UPDATED_AT = 'LastUpdateDate';

	// protected $dateFormat = 'Y-m-d H:i:s.v';
    protected $table = 'gnMstCustomer';
    protected $primaryKey = 'CustomerCode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 'CustomerCode', 'StandardCode', 'CustomerName', 'CustomerAbbrName', 'CustomerGovName', 'CustomerType', 'CategoryCode', 'Address1', 'Address2', 'Address3', 'Address4', 'PhoneNo', 'HPNo', 'FaxNo', 'isPKP', 'NPWPNo', 'NPWPDate', 'SKPNo', 'SKPDate', 'ProvinceCode', 'AreaCode', 'CityCode', 'ZipNo', 'Status', 'CreatedBy', 'CreatedDate', 'LastUpdateBy', 'LastUpdateDate', 'isLocked', 'LockingBy', 'LockingDate', 'Email', 'BirthDate', 'Spare01', 'Spare02', 'Spare03', 'Spare04', 'Spare05', 'Gender', 'OfficePhoneNo', 'KelurahanDesa', 'KecamatanDistrik', 'KotaKabupaten', 'IbuKota', 'CustomerStatus',
    ];

}
