
@extends('layouts.app', ['title' => __('admin.page.detail',['attribute'=>__('admin.page.banner.banner')])])

@section('content')
<style type="text/css">
    .cursor-pointer {
        cursor: pointer;
    }

    .banner-image-wrap {
        height: 200px;
        overflow: hidden;
        background-color: #f5f6f9;
        max-width: 100%;
        border-radius: 0.25rem !important;
        /*border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: 0.25rem;*/
    }

    .banner-image-wrap img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }

    .banner-wrap p {
        font-size: 15px;
    }

    .banner-wrap {
        cursor: pointer;
    }

    .banner-wrap:hover img {
        transform: scale(1.1);
        transition: transform .4s;
        filter: sepia(50%);
    }

    .banner-imvisibilityage-wrap.add-new-block p {
        font-size: 24px;
    }

    .banner-image-wrap.add-new-block:hover {
        background-color: #ddd;
        transition: all .4s ease-in-out 0s;
    }

    .banner-btn-wrap {
        height: 200px;
        overflow: hidden;
        max-width: 100%;
        border-radius: 0.25rem !important;
        cursor: pointer;
    }

    .banner-wrap:hover i {
        transform: scale(1.1);
        transition: transform .4s;
        filter: sepia(50%);
    }

    .banner-btn-wrap:hover {
        background-color: #6b9fec !important;
    }
</style>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <!-- end page title -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <!-- project card -->
            <div class="card d-block">
                <div class="card-body">
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                            <p ><strong>General:</strong></p>
                                <div class="col-4">
                                    <label class="mt-2 mb-1">Name :</label>
                                    <p>{{$cmsdetail->name}}</p>
                                </div>
                                <div class="col-4">
                                    <label class="mt-2 mb-1">Title :</label>
                                    <p>{{$cmsdetail->title}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="mt-2 mb-1">Description :</label>
                                   <p> {!! $cmsdetail->description !!}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                            <p ><strong>Meta Tags:</strong></p>
                                <div class="col-4">
                                    <label class="mt-2 mb-1">Meta Title :</label>
                                    <p>{{$cmsdetail->meta_title}}</p>
                                </div>
                                <div class="col-4">
                                    <label class="mt-2 mb-1">Meta Keywords :</label>
                                    <p>{{$cmsdetail->meta_keyword}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="mt-2 mb-1">Meta Description :</label>
                                   <p> {{$cmsdetail->meta_description}}</p>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-3">
                <a href="{{route('admin.cms.edit', ['id' => $cmsdetail->id])}}" class="btn w-sm btn-success waves-effect waves-light">Edit</a>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->
@endsection
@section('script')
<script type="text/javascript">
    $("#sidebar-menu #side-menu li.banners,#sidebar-menu #side-menu li.banners-list").addClass("menuitem-active");
    $("#sidebarbanner").addClass("show");
</script>
@endsection


