@extends('layouts.app')

@section('title')
Create PEP
@endsection

@section('content')
<div class="bg-light rounded">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail</h5>
            <hr/>
            <div class="p-4 rounded">
                <div class="container mt-4">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for='name'>Nama</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="nama" id='nama' value='{{ $pepDetail->name}}' disabled>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for='name'>NIK</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="nik" id='nik' value='{{ $pepDetail->nik}}' disabled>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for='name'>Tempat Lahir</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="tempat_lahir" id='tempat_lahir' value='{{ $pepDetail->tempat_lahir}}' disabled>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for='name'>Tanggal Lahir</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="tanggal_lahir" id='tanggal_lahir' value="{{ date('d-m-Y', strtotime($pepDetail->tanggal_lahir)) }}" disabled>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for='name'>Jabatan</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="jabatan" id='jabatan' value='{{ $pepDetail->jabatan}}' disabled>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for='name'>Instansi</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="instansi" id='instansi' value='{{ $pepDetail->instansi}}' disabled>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label for='name'>Provinsi</label>
                        </div>
                        <div class="col-md-8 mb-4">
                            <input type="text" class='form-control' name="provinsi" id='provinsi' value='{{ $pepDetail->provinsi}}' disabled>
                        </div>                       
                    </div>
                    <hr/>    
                    <div class="float-end">                    
                        <a href="{{ route('pepCheck.index') }}" class="btn btn-info btn-sm">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection