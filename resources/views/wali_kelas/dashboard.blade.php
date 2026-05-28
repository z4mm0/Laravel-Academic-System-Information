@extends('layout.app')

@section('title', 'Wali Kelas Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Dashboard Wali Kelas</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-school"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Kelas</span>
                            <span class="info-box-number">{{ $classes->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Siswa</span>
                            <span class="info-box-number">{{ $totalStudents }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Kelas Anda</h3>
                        </div>
                        <div class="card-body">
                            @foreach($classes as $class)
                                <a href="{{ route('wali_kelas.class.grades', $class) }}" class="btn btn-primary btn-block mb-2">{{ $class->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
