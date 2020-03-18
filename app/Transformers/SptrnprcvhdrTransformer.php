<?php

namespace App\Transformers;

use App\Sptrnprcvhdr;
use League\Fractal\TransformerAbstract;

class SptrnprcvhdrTransformer extends TransformerAbstract
{

    public function transform(Sptrnprcvhdr $sptrnprcvhdr)
    {
        return [
            'CompanyCode' => $sptrnprcvhdr->CompanyCode,
            'BranchCode' => $sptrnprcvhdr->BranchCode,
            'WRSNo' => $sptrnprcvhdr->WRSNo,
        ];
    }
}
