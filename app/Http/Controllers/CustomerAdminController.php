<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%") ;
            });
        }
        $customers = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('customers', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with('orders')->findOrFail($id);
        return view('customer_detail', compact('customer'));
    }
}
