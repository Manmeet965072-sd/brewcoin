@extends('layouts.app', ['title' => 'Coupon Codes'])
@section('content')
<!-- Start Content-->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Coupon Code Manager</h4>
        {{--<div class="card-search"></div>--}}
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">

                        <div class="col-lg-5">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                {{-- <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i class="mdi mdi-cog"></i></button> --}}
                                <a href="{{route('admin.coupons.add')}}" class="btn btn-danger waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Add</a>
                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-box -->
            </div> <!-- end col-->
        </div>
    </div>
    <div class="card-body">
        @if($coupons->total() > 0)
        <div class="table-responsive" style="min-height: 80vh">
            <table class="table table-hover custom-data-bs-table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Code</th>
                        <th scope="col">Discount Type</th>
                        <th scope="col">Discount Value</th>
                        <th scope="col">Valid Upto</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td data-label="User">
                            <span class="sub sub-s2 sub-dtype">{{ $coupon->title }}</span>
                        </td>
                        <td data-label="User">
                            <span class="sub sub-s2 sub-dtype">{{ $coupon->code }}</span>
                        </td>
                        <td data-label="User">
                            <span class="sub sub-s2 sub-dtype">{{ $coupon->discount_type }}</span>
                        </td>
                        <td data-label="User">
                            <span class="sub sub-s2 sub-dtype">{{ $coupon->discount_value }}</span>
                        </td>
                        <td data-label="Doc Type">
                            <span class="sub sub-s2 sub-dtype">{{ $coupon->valid_upto }}</span>
                        </td>

                        <td data-label="Status">
                            <span class="dt-status-md badge badge-md badge-{{ __status($coupon->is_active,'status') }}">{{ $coupon->is_active==1?'Active':'In-Active' }}</span>
                        </td>
                        <td data-label="Actions">
                            <div class="product-action">

                                <a href="{{route('admin.coupons.edit', ['id' => $coupon->id])}}" class="btn btn-success btn-xs waves-effect waves-light"><i class="bi bi-pencil"></i></a>
                                <a href="{{route('admin.coupons.delete', ['id' => $coupon->id])}}" onclick="return myFunction();" class="btn btn-danger btn-xs waves-effect waves-light"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-light text-center rounded py-2">
            <p><i class="bi bi-arrow-down"></i><br>{{'No Coupons found!' }}</p>

        </div>
        @endif
    </div>
    <div class="mb-1">{{ paginateLinks($coupons) }}</div>
</div>


<script>
    function myFunction() {
        if (!confirm("Are You Sure to delete this"))
            event.preventDefault();
    }
</script>
@endsection