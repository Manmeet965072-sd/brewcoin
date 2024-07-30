@extends('layouts.app', ['title' => 'Edit CMS'])

@section('css')
<!-- Plugins css -->

@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <h5 class="text-uppercase bg-light p-2 mt-0 mb-3">General</h5>
                <form action="{{route('admin.cms.update', ['id' => $cmsdetail->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="product-name">Page Name <span class="text-danger">*</span></label>
                        <input type="text" id="product-name" name="name" class="form-control" placeholder="e.g : Apple iMac" value="{{$cmsdetail->name}}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="page-title">Page Title <span class="text-danger">*</span></label>
                        <input type="text" id="page-title" name="title" class="form-control" placeholder="e.g : Apple iMac" value="{{$cmsdetail->title}}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="product-description">Page Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="product-description1" name="description" rows="5" placeholder="Please enter description">{{$cmsdetail->description}}</textarea>
                    </div>
            </div> <!-- end card-box -->
        </div> <!-- end col -->
        <div class="col-lg-6">
            <div class="card-box">
                <h5 class="text-uppercase mt-0 mb-3 bg-light p-2">Meta Fields</h5>
                <div class="form-group mb-3">
                    <label for="meta-title">Meta Title <span class="text-danger"></span></label>
                    <input type="text" id="meta-title" name="meta_title" class="form-control" placeholder="e.g : page title" value="{{$cmsdetail->meta_title}}">
                </div>
                <div class="form-group mb-3">
                    <label for="meta-keywords">Meta Keywords <span class="text-danger"></span></label>
                    <input type="text" id="meta-keywords2" name="meta_keyword" class="form-control" placeholder="e.g : meta tags" data-role="tagsinput" value="{{$cmsdetail->meta_keyword}}">
                </div>
                <div class="form-group mb-3">
                    <label for="meta-description">Meta Description <span class="text-danger"></span></label>
                    <textarea name="meta_description" id="meta-description" class="form-control" cols="30" rows="3">{{$cmsdetail->meta_description}}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Status <span class="text-danger">*</span></label>
                    <br />
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio1" value="1" name="status" {{ ($cmsdetail->status==1)?"checked":"" }}>
                        <label for="inlineRadio1"> Active </label>
                    </div>
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio2" value="2" name="status" {{ ($cmsdetail->status==2)?"checked":"" }}>
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
                <button type="submit" class="btn w-sm btn-success waves-effect waves-light">Update</button>
            </div>
        </div> <!-- end col -->
    </div>
    </form>
    <!-- end row -->
</div> <!-- container -->
@endsection

@section('script')
<!-- Plugins js-->

@endsection