@extends('layouts.app')

@section('title', 'Importar Dependencias')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-3 p-4">
        <h3 class="mb-4"><i class="bi bi-file-earmark-excel text-success"></i> Importar Dependencias (Hoja 1)</h3>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('dependencias.importar.procesar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="archivo" class="form-label fw-semibold">Seleccionar archivo Excel</label>
                <input type="file" name="archivo" id="archivo" class="form-control" accept=".xls,.xlsx" required>
            </div>

            <button type="submit" class="btn btn-success fw-semibold px-4">
                <i class="bi bi-upload"></i> Importar
            </button>
        </form>
    </div>
</div>
@endsection
