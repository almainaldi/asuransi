<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function getCustomers()
    {
        $customers = Customer::all();

        return view('customer.list')->with(compact('customers'));
    }

    public function createCustomer()
    {
        return view('customer.form');
    }

    public function insertCustomer(Request $request)
    {
        $customer = new Customer();

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->age = $request->age;

        $customer->save();

        return redirect('/dashboard');
    }

    public function showFormUpdate($customer_id)
    {
        $customer = Customer::find($customer_id);

        return view('customer.edit')->with(compact('customer'));
    }

    public function updateCustomer(Request $request, $customer_id)
    {
        $customer = Customer::find($customer_id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->age = $request->age;

        $customer->save();

        return redirect('/dashboard');
    }

    public function deleteCustomer($customer_id)
    {
        $customer = Customer::find($customer_id);

        if ($customer) {
            $customer->delete();
        }

        return redirect('/dashboard');
    }
}