<?php

namespace App\Transformers;

use App\Gnmstcustomer;
use League\Fractal\TransformerAbstract;

class GnmstcustomerTransformer extends TransformerAbstract
{
    
    public function transform(Gnmstcustomer $gnmstcustomer)
    {
        return [
            'CompanyCode' => $gnmstcustomer->CompanyCode,
            'CustomerCode' => $gnmstcustomer->CustomerCode,
            'StandardCode' => $gnmstcustomer->StandardCode,
            'CustomerName' => $gnmstcustomer->CustomerName,
            'LastUpdateDate' => $gnmstcustomer->LastUpdateDate,
        ];
    }
}
