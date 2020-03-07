<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class GnmstsupplierTransformer extends TransformerAbstract
{
    
    public function transform()
    {
        return [
            'SupplierCode' => $gnmstcustomer->CompanyCode,
            'SupplierName' => $gnmstcustomer->SupplierName,
        ];
    }
}
