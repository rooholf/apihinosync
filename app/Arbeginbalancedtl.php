<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Arbeginbalancedtl extends Model
{
    protected $table = 'arBeginBalanceDtl';
    protected $primaryKey = ['CompanyCode', 'BranchCode', 'DocNo', 'SeqNo'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'CompanyCode', 
        'BranchCode', 
        'DocNo', 
        'SeqNo', 
        'AccountNo', 
        'Description', 
        'Amount', 
        'Status', 
        'CreatedBy', 
        'CreatedDate',
    ];
}
