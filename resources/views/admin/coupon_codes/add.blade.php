@extends('layouts.app', ['title' => 'Add coupon'])

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

                <form action="{{route('admin.coupons.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="product-name">Title <span class="text-danger">*</span> </label>
                        <input type="text" id="title" name="title" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="page-title">Code <span class="text-danger">*</span></label>
                        <input type="text" id="code" name="code" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="page-title">Description <span class="text-danger"></span></label>
                        <textarea id="description" name="description" class="form-control" rows="4" cols="50"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="page-title">Supply<span class="text-danger">*</span></label>
                        <input type="number" id="supply" name="supply" class="form-control">
                    </div>
            </div> <!-- end card-box -->
        </div> <!-- end col -->
        <div class="col-lg-6">
            <div class="card-box">
                <div class="form-group mb-3">
                    <label for="page-title">Frequency<span class="text-danger"></span></label>
                    <input type="number" id="frequency" name="frequency" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="page-title">Discount Type<span class="text-danger">*</span></label>
                    <select class="form-control" name="discount_type">
                        <option value="fixed">Fixed</option>
                        <option value="fixed">Percentage</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="page-title">Discount Value<span class="text-danger">*</span></label>
                    <input type="number" id="discount_value" step="0.01" name="discount_value" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="page-title">Valid Upto<span class="text-danger">*</span></label>
                    <input type="date" id="valid_upto" name="valid_upto" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label class="mb-2">Status <span class="text-danger"></span></label>
                    <br />
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio1" value="1" name="is_active" checked="">
                        <label for="inlineRadio1"> Active </label>
                    </div>
                    <div class="radio form-check-inline">
                        <input type="radio" id="inlineRadio2" value="0" name="is_active">
                        <label for="inlineRadio2"> Inactive </label>
                    </div>
                </div>
            </div>
        </div>

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