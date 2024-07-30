@extends('layouts.app', ['title' => 'Banner'])
@section('content')
<!-- Start Content-->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Banner Manager</h4>
        {{--<div class="card-search"></div>--}}
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">

                        <div class="col-lg-5">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                {{-- <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i class="mdi mdi-cog"></i></button> --}}
                                <a href="{{route('admin.banners.add')}}" class="btn btn-danger waves-effect waves-light" data-toggle="modal" data-target="#add"><i class="mdi mdi-plus-circle mr-1"></i> Add</a>
                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-box -->
            </div> <!-- end col-->
        </div>
    </div>
    <div class="card-body">
        {{--<div class="page-nav-wrap border-0">
            <div class="page-nav-bar p-0">
                <div class="page-nav w-100 w-lg-auto">
                    <ul class="nav border-bottom">
                        <li class="nav-item{{ (is_page('kyc-list.pending') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.kycs', 'pending') }}">Pending</a></li>

        <li class="nav-item{{ (is_page('kyc-list.missing') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.kycs', 'missing') }}">Missing</a></li>

        <li class="nav-item{{ (is_page('kyc-list.approved') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.kycs', 'approved') }}">Approved</a></li>

        <li class="nav-item{{ (is_page('kyc-list') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.kycs') }}">All</a></li>
        </ul>
    </div>
</div>
</div>--}}

@if($pages->total() > 0)
<div class="table-responsive" style="min-height: 80vh">
    <table class="table table-hover custom-data-bs-table">
        <thead class="table-light">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Banner</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pages as $banner)
            <tr>
                <td data-label="User">
                    <span class="sub sub-s2 sub-dtype">{{ $banner->id }}</span>
                </td>
                <td data-label="Doc Type">
                    <img src="{{ $banner->banner_url }}" class="sub sub-s2 sub-dtype" />
                </td>
                <td data-label="Status">
                    <span class="dt-status-md badge badge-md badge-{{ __status($banner->is_active,'status') }}">{{ $banner->is_active==1?'Active':'In-Active' }}</span>
                </td>
                <td data-label="Actions">
                    <div class="product-action">

                        <a href="{{route('admin.banners.edit',$banner->id)}}" class="btn btn-success btn-xs waves-effect waves-light"><i class="bi bi-pencil"></i></a>
                        <a href="{{route('admin.banners.delete', ['id' => $banner->id])}}" onclick="return myFunction();" class="btn btn-danger btn-xs waves-effect waves-light"><i class="bi bi-trash"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-light text-center rounded py-2">
    <p><i class="bi bi-arrow-down"></i><br>{{'No Banners found!' }}</p>

</div>
@endif
<div class="mb-1">{{ paginateLinks($pages) }}</div>
</div>
</div>

<script>
    function myFunction() {
        if (!confirm("Are You Sure to delete this"))
            event.preventDefault();
    }
</script>
@endsection