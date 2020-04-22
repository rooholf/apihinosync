<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sptrnsfpjinfo extends Model
{
    protected $table = 'spTrnSFPJInfo';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'FPJNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'FPJNo', 
        'CustomerName', 
        'Address1', 
        'Address2', 
        'Address3', 
        'Address4', 
        'isPKP', 
        'NPWPNo', 
        'SKPNo', 
        'SKPDate', 
        'NPWPDate', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate',
    ];
}
