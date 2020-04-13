<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apbeginbalancedtl extends Model
{
    protected $table = 'apBeginBalanceDtl';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'DocNo', 'SeqNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
    	'CompanyCode', 'BranchCode', 'DocNo', 'SeqNo', 'AccountNo', 'Description', 'Amount', 'Status', 'CreatedBy', 'CreatedDate',
    ];
}
