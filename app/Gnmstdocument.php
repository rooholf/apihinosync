<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gnmstdocument extends Model
{
    protected $table = 'gnMstDocument';
	protected $primaryKey = 'DocumentType';
	public $incrementing = false;
	public $timestamps = false;

	protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'DocumentType', 
        'DocumentName', 
        'DocumentPrefix', 
        'ProfitCenterCode', 
        'DocumentYear', 
        'DocumentSequence', 
        'CreatedBy', 
        'CreatedDate', 
        'LastUpdateBy', 
        'LastUpdateDate', 
        'isLocked', 
        'LockingBy', 
        'LockingDate',
	];
}
