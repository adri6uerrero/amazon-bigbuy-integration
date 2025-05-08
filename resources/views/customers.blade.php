@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Clientes</span>
                    <form method="GET" action="{{ route('customers.index') }}" class="d-flex mb-0">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2" placeholder="Buscar nombre, email o dirección">
                        <button class="btn btn-outline-light btn-sm">Buscar</button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Dirección</th>
                                <th>Pedidos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->address }}</td>
                                    <td>{{ $customer->orders()->count() }}</td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay clientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <nav>
                        <ul class="pagination mb-0">
                            @if ($customers->onFirstPage())
                                <li class="page-item disabled"><span class="page-link"><span class="me-1">&#8592;</span> Previous</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $customers->previousPageUrl() }}" rel="prev"><span class="me-1">&#8592;</span> Previous</a></li>
                            @endif

                            @if ($customers->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $customers->nextPageUrl() }}" rel="next">Next <span class="ms-1">&#8594;</span></a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Next <span class="ms-1">&#8594;</span></span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
