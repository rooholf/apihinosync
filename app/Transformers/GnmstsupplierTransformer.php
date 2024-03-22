<?php

namespace App\Transformers;

use App\Gnmstsupplier;
use League\Fractal\TransformerAbstract;

class GnmstsupplierTransformer extends TransformerAbstract
{
    
    public function transform(Gnmstsupplier $gnmstsupplier)
    {
        return [
	    'CompanyCode' => $gnmstsupplier->CompanyCode,
            'SupplierCode' => $gnmstsupplier->CompanyCode,
            'SupplierName' => $gnmstsupplier->SupplierName,
        ];
    }
}
