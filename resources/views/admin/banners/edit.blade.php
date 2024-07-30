@extends('layouts.app', ['title' => 'Edit Banner'])

@section('css')
<!-- Plugins css -->
<link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/summernote/summernote.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" />

@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">

                <form action="{{route('admin.banners.update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden"  name="id" class="form-control" value="{{$bannerdetail->id}}">
                    <div class="form-group mb-3">
                        <label for="product-name">Banner Image<span class="text-danger">*</span> </label>
                        <input type="file" id="banner" name="banner" class="form-control" value="{{$bannerdetail->banner_url}}" onchange="readURL(this);">

                    </div>
                    <div class="form-group mb-3">
                        <label for="product-name">Banner Link<span class="text-danger">*</span> </label>
                        <input type="text" id="link" name="link" class="form-control" value="{{$bannerdetail->link}}">

                    </div>

            </div> <!-- end card-box -->
        </div> <!-- end col -->
        <div class="col-lg-6">
            <div class="card-box">
                <img id="blah" src="{{$bannerdetail->banner_url}}" alt="your image" />
            </div> <!-- end card-box -->
        </div> <!-- end col -->

    </div>
    <div class="row">
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