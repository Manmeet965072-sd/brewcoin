@extends('layouts.app', ['title' => 'Add CMS'])

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
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>
                <form action="{{route('admin.cms.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="product-name">Page Name (without spaces) <span class="text-danger">*</span> </label>
                        <input type="text" id="product-name" name="name" class="form-control" placeholder="Aboutus" pattern="^\S+$">
                    </div>
                    <div class="form-group mb-3">
                        <label for="page-title">Page Title <span class="text-danger">*</span></label>
                        <input type="text" id="page-title" name="title" class="form-control" placeholder="e.g : Apple iMac">
                    </div>
                    <div class="form-group mb-3">
                        <label for="product-description">Page Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="product-description2" name="description" rows="5" placeholder="Please enter description"></textarea>
                    </div>
            </div> <!-- end card-box -->
        </div> <!-- end col -->
        <div class="col-lg-6">
            <div class="card-box">
                <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Meta Fields</h5>
                <div class="form-group mb-3">
                    <label for="meta-title">Meta Title <span class="text-danger"></span></label>
                    <input type="text" id="meta-title" name="meta_title" class="form-control" placeholder="e.g : page title">
                </div>
                <div class="form-group mb-3">
                    <label for="meta-keywords">Meta Keywords <span class="text-danger"></span></label>
                    <input type="text" id="meta-keywords1" name="meta_keyword" class="form-control" data-role="tagsinput" placeholder="e.g : meta tags">
                </div>
                <div class="form-group mb-3">
                    <label for="meta-description">Meta Description <span class="text-danger"></span></label>
                    <textarea name="meta_description" id="meta-description" class="form-control" cols="30" rows="3"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Status <span class="text-danger">*</span></label>
                    <br />
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio1" value="1" name="status" checked="">
                        <label for="inlineRadio1"> Active </label>
                    </div>
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio2" value="2" name="status">
                        <label for="inlineRadio2"> Inactive </label>
                    </div>
                </div>
            </div> <!-- end col-->
        </div> <!-- end col-->
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
    <div class="d-none" id="uploadPreviewTemplate">
        <div class="card mt-1 mb-0 shadow-none border">
            <div class="p-2">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img data-dz-thumbnail src="#" class="avatar-sm rounded bg-light" alt="">
                    </div>
                    <div class="col pl-0">
                        <a href="javascript:void(0);" class="text-muted font-weight-bold" data-dz-name></a>
                        <p class="mb-0" data-dz-size></p>
                    </div>
                    <div class="col-auto">
                        <!-- Button -->
                        <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove>
                            <i class="dripicons-cross"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
@endsection

@section('script')
<!-- Plugins js-->

@endsection