<?php

namespace App\Transformers;

use App\Spmstitem;
use League\Fractal\TransformerAbstract;


class SpmstitemTransformer extends TransformerAbstract
{

    public function transform(Spmstitem $spmstitem)
    {
        return [
            'CompanyCode' => $spmstitem->CompanyCode,
            'PartNo' => $spmstitem->PartNo,
        ];
    }
}
