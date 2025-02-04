@extends('layouts.app')

@section('title')
PEP Check
@endsection

@section('content')
<div class="bg-light rounded">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">PEP Check</h5>
            <h6 class="card-subtitle mb-2 text-muted"> PEP List</h6>

            <form action="{{ route('pepCheck.index') }}" method="GET">
                @csrf
                <div class="mb-3 text-end">
                    <a href="{{ route('pepCheck.create') }}" class="btn btn-primary btn-sm float-right">+ Add PEP</a>
                    <a href="#" id="exportLink" class="btn btn-primary btn-sm float-right">Export Data</a>
                </div>

                <div class="row">
                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="pencarian" id="searchInput" placeholder="Keyword" value="{{ request()->input('pencarian') }}">
                            <button class="input-group-text btn btn-primary">Search</button>
                        </div>
                    </div>

                    <!-- <div class="col-md-3">
                        <div class="input-group mb-3">
                            <label for="datepicker">Date:</label>
                            <input type="text" class="form-control" id="daterange" placeholder="Pilih rentang tanggal">
                        </div>
                    </div> -->
                </div>                
            </form>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="3%">No</th>
                        <th scope="col">Name</th>
                        <th scope="col" width="15%">NIK</th>
                        <th scope="col" width="15%">Jabatan</th>
                        <th scope="col" width="15%">Instansi</th>                        
                        <th scope="col" width="15%">Created At</th>
                        <th scope="col" width="10%" colspan="3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pepList as $data)
                    <tr>
                        <td>{{ $pepList->firstItem() + $loop->index }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>{{ $data->jabatan }}</td>
                        <td>{{ $data->instansi }}</td>
                        <td>{{ $data-> created_at}}</td>
                        <td><a href="pepCheck/detail/{{$data->id}}" class="btn btn-info btn-sm">Detail</a></td>
                        <td>
                            <!-- <a href="pepCheck/delete/{{$data->id}}" class="btn btn-danger btn-sm">Delete</a> -->
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $data->id }}">Delete</a>

                        </td>

                    </tr>
                    @endforeach
            </table>
            <div class='my-3 float-end'>
                {{$pepList->withQueryString()->links()}}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.getElementById('exportLink').addEventListener('click', function(event) {
        event.preventDefault(); // Mencegah tautan default

        var keyword = document.getElementById('searchInput').value;

        // Buat URL untuk ekspor data dengan parameter pencarian
        var exportUrl = "{{ route('pepCheck.download') }}?pencarian=" + encodeURIComponent(keyword);

        // Arahkan ke URL ekspor
        window.location.href = exportUrl;
    });

    $(document).ready(function() {
        $(".btn-delete").click(function(e){
            e.preventDefault();
            var userId = $(this).data("id");
            var url = "pepCheck/delete/" + userId;        

            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        alert(response.message);
                    }
                });
            }
            

        });
    });
    
</script>


@endsection

