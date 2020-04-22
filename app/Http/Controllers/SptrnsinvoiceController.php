<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SptrnsinvoiceController extends Controller
{
    public function add(Request $request)
    {
    	$this->validate($request, [
            'GRNo' => 'required',
        ]);
    }
}
