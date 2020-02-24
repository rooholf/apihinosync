<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB
use Illuminate\Support\Str; //

use App\Gnmstcustomer; //model
use App\Transformers\GnmstcustomerTransformer; //transformer
use Auth;


class GnmstcustomerController extends Controller
{
    public function show(Gnmstcustomer $gnmstcustomer, $id)
    {
    	$customer = $gnmstcustomer->find($id);
    	// dd($customer);
    	return fractal()
    			->item($customer)
    			->transformWith(new GnmstcustomerTransformer)
    			->toArray();
    }

    public function add(Request $request, Gnmstcustomer $gnmstcustomer)
    {
        $this->validate($request, [
            'name' => 'required', 
            'contact_person' => 'required', 
            'phone' => 'required', 
            'email' => 'required|email|unique:tbl_suppliers', 
        ]);

        $user_id = Auth::user()->useradmin->id;

        $supplier = $supplier->create([
            'id' => Str::uuid(),
            'name' => $request->name, 
            'contact_person' => $request->contact_person, 
            'phone' => $request->phone, 
            'email' => $request->email, 
            'address' => $request->address, 
            'description' => $request->description, 
            'user_id' => $user_id, 
            'del_status' => "Live", 
        ]);

        return fractal()
            ->item($supplier)
            ->transformWith(new SupplierTransformer)
            ->toArray();

    }

    public function update(Request $request, Gnmstcustomer $gnmstcustomer)
    {

        $supplier->name = $request->get('name', $supplier->name);
        $supplier->contact_person = $request->get('contact_person', $supplier->contact_person);
        $supplier->phone = $request->get('phone', $supplier->phone);
        $supplier->email = $request->get('email', $supplier->email);
        $supplier->address = $request->get('address', $supplier->address);
        $supplier->description = $request->get('description', $supplier->description);
        $supplier->del_status = $request->get('del_status', $supplier->del_status);

        $supplier->save();

        return fractal()
            ->item($supplier)
            ->transformWith(new SupplierTransformer)
            ->toArray();
    }
}
