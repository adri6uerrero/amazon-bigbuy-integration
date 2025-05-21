<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Muestra un listado de clientes.
     */
    public function index(Request $request)
    {
        $query = Customer::query();
        
        // Aplicar filtros de búsqueda si se proporciona
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
            });
        }
        
        $customers = $query->orderBy('created_at', 'desc')
                          ->paginate(10);
                          
        return view('customers', compact('customers'));
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $customer = Customer::create($validated);
        
        return redirect()->route('customers.show', $customer->id)
                         ->with('success', 'Cliente creado con éxito.');
    }

    /**
     * Muestra la información detallada de un cliente.
     */
    public function show(Customer $customer)
    {
        $customer->load('orders');
        
        return view('customer_detail', compact('customer'));
    }

    /**
     * Muestra el formulario para editar un cliente.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Actualiza la información de un cliente.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $customer->update($validated);
        
        return redirect()->route('customers.show', $customer->id)
                         ->with('success', 'Cliente actualizado con éxito.');
    }

    /**
     * Elimina un cliente.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        return redirect()->route('customers.index')
                         ->with('success', 'Cliente eliminado con éxito.');
    }
}
