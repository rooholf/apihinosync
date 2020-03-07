<?php

namespace App\Transformers;

use App\Gnmstsupplier;
use League\Fractal\TransformerAbstract;

class GnmstsupplierTransformer extends TransformerAbstract
{
    
    public function transform(Gnmstsupplier $gnmstsupplier)
    {
        return [
            'SupplierCode' => $gnmstcustomer->CompanyCode,
            'SupplierName' => $gnmstcustomer->SupplierName,
        ];
    }
}
