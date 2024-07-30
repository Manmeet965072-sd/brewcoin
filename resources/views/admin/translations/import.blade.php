@extends('layouts.app', ['title' => 'Import Translation'])

@section('css')
<!-- Plugins css -->
@endsection

@section('content')
<!-- Start Content-->
@if(Session::has('message'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
    @php
    Session::forget('error');
    @endphp
</div>
@endif
@if(Session::has('message'))
<div class="alert alert-success">
    {{ Session::get('message') }}
    @php
    Session::forget('message');
    @endphp
</div>
@endif
<div class="container-fluid">
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">

                <form action="{{ route('admin.translations.import') }}" method="POST" enctype="multipart/form-data" id="import_translations">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Upload excel file</label>
                        <div class="input-group input-group-merge input-group-alternative">
                            <input type="file" class="form-control" placeholder="Upload file" name="upload_excel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                        </div>
                    </div>
            </div> <!-- end card-box -->
        </div> <!-- end col -->

    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-3">
                <button type="reset" class="btn w-sm btn-light waves-effect">Cancel</button>
                <button type="submit" class="btn w-sm btn-success waves-effect waves-light">Save</button>
            </div>
        </div> <!-- end col -->
    </div>
    </form>
    <!-- end row -->
    <!-- file preview template -->

</div> <!-- container -->
@endsection

@section('script')
<!-- Plugins js-->


@endsection