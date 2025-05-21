@extends('layouts.app')

@section('content')
<style>
    /* Header styles */
    .customers-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .customers-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .customers-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    .search-box {
        background: rgba(255,255,255,0.15);
        border: none;
        border-radius: 50px;
        padding: 8px 15px;
        color: white;
        width: 250px;
        transition: all 0.3s ease;
    }
    
    .search-box::placeholder {
        color: rgba(255,255,255,0.7);
    }
    
    .search-box:focus {
        background: rgba(255,255,255,0.25);
        box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
    }
    
    /* Card styles */
    .customer-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(61, 110, 234, 0.06);
        overflow: hidden;
        border: 1px solid rgba(230, 230, 250, 0.7);
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .customer-card:hover {
        box-shadow: 0 8px 20px rgba(61, 110, 234, 0.1);
        transform: translateY(-2px);
    }
    
    /* Table styles */
    .customer-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .customer-table th {
        padding: 16px 20px;
        font-weight: 600;
        color: #444;
        border-bottom: 1px solid #eee;
        background-color: #f9fafc;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .customer-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
    }
    
    .customer-table tbody tr:hover {
        background-color: #f5f9ff;
    }
    
    /* Button styles */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        color: #536080;
        background: #f7f9fc;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .action-btn:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: #3d6eea;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="customers-header">
        <div>
            <h2>Clientes</h2>
            <p>Gestión de clientes para Amazon-BigBuy</p>
        </div>
        <form method="GET" action="{{ route('customers.index') }}" class="d-flex mb-0">
            <input type="text" name="search" value="{{ request('search') }}" class="search-box" placeholder="Buscar cliente...">
            <button type="submit" class="btn btn-light ms-2 rounded-pill px-3">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    
    <div class="customer-card">
                <div class="p-0">
                    <table class="customer-table mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Dirección</th>
                                <th class="text-center">Pedidos</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex justify-content-center align-items-center me-3" 
                                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%); 
                                                        border-radius: 10px; color: white; font-weight: 600;">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="font-weight: 500; color: #333;">{{ $customer->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill" 
                                              style="background: linear-gradient(135deg, #42b883 0%, #3d6eea 100%); 
                                                     padding: 6px 12px; font-weight: 500; font-size: 0.85rem;">
                                            {{ $customer->orders()->count() }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('customers.show', $customer->id) }}" class="action-btn">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="bi bi-people text-muted mb-3" style="font-size: 2.5rem;"></i>
                                            <p class="mb-1" style="font-weight: 500; color: #444;">No se encontraron clientes</p>
                                            <p class="text-muted" style="font-size: 0.9rem;">Los clientes aparecerán aquí cuando se creen</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 d-flex justify-content-end">
                    <nav>
                        <ul class="pagination mb-0">
                            @if ($customers->onFirstPage())
                                <li class="page-item disabled"><span class="page-link" style="border-radius: 10px 0 0 10px;"><span class="me-1">&#8592;</span> Anterior</span></li>
                            @else
                                <li class="page-item"><a href="{{ $customers->previousPageUrl() }}" class="page-link" style="border-radius: 10px 0 0 10px;" rel="prev"><span class="me-1">&#8592;</span> Anterior</a></li>
                            @endif

                            @if ($customers->hasMorePages())
                                <li class="page-item"><a href="{{ $customers->nextPageUrl() }}" class="page-link" style="border-radius: 0 10px 10px 0;" rel="next">Siguiente <span class="ms-1">&#8594;</span></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link" style="border-radius: 0 10px 10px 0;">Siguiente <span class="ms-1">&#8594;</span></span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
