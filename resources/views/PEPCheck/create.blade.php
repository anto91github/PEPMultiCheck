@extends('layouts.app')

@section('title')
Create PEP
@endsection

@section('content')
<div class="bg-light rounded">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Bulk check PEP</h5>
            <div class="p-4 rounded">
                <div class="container mt-4">
                @if (Session::has('statusUpload'))
                    @if (Session::get('statusUpload') == 'success')
                        <div class="alert alert-success" id="flash-message" role="alert">
                            {{Session::get('messageUpload')}}
                        </div>
                    @else
                        <div class="alert alert-danger" id="flash-message" role="alert">
                            {{Session::get('messageUpload')}}
                        </div>
                    @endif        
                @endif

                    <form method="POST" action="{{ route('pepCheck.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Upload File :</label>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mengatur timeout untuk menghilangkan alert setelah 5 detik (5000 ms)
    setTimeout(function() {
        var flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            flashMessage.style.display = 'none'; // Menghilangkan alert
        }
    }, 5000); // 5000 ms = 5 detik
</script>
@endsection