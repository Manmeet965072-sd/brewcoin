@extends('layouts.app', ['title' => 'Add language'])

@section('css')
<!-- Plugins css -->
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">

                <form action="{{route('admin.language.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="product-name">Language Name <span class="text-danger">*</span> </label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="page-title">Language Code <span class="text-danger">*</span></label>
                        <input type="text" id="lang_code" name="lang_code" class="form-control">
                    </div>

                    <div class="form-group mb-3">
                        <label class="mb-2">Status <span class="text-danger">*</span></label>
                        <br />
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio1" value="1" name="status" checked="">
                            <label for="inlineRadio1"> Active </label>
                        </div>
                        <div class="radio form-check-inline">
                            <input type="radio" id="inlineRadio2" value="0" name="status">
                            <label for="inlineRadio2"> Inactive </label>
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