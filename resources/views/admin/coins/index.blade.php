@extends('layouts.app', ['title' => 'Assets'])
@section('content')
<!-- Start Content-->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Assets Manager</h4>
        {{--<div class="card-search"></div>--}}
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">

                        <div class="col-lg-5">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                {{-- <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i class="mdi mdi-cog"></i></button> --}}
                                {{--<a href="{{route('admin.cms.add')}}" class="btn btn-danger waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Add</a>--}}
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


<div class="table-responsive" style="min-height: 80vh">
    <table class="table table-hover custom-data-bs-table">
        <thead class="table-light">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Symbol</th>
                <th scope="col">Asset Id</th>
                <th scope="col">Status</th>

            </tr>
        </thead>
        <tbody>
            @foreach($coins as $coin)
            <tr>
                <td data-label="User">
                    <span class="sub sub-s2 sub-dtype">{{ $coin->name }}</span>
                </td>
                <td data-label="Doc Type">
                    <span class="sub sub-s2 sub-dtype">{{ $coin->symbol }}</span>
                </td>
                <td data-label="Date description">
                    <span class="sub sub-s2 sub-time">{{$coin->coin_id}}</span>
                </td>
                <td data-label="Status">
                    <div class="product-action" style="width:200px;">
                        <select class="btn w-sm btn-xs btn-success  waves-effect waves-light statusactive" id="statusactive" data-id="{{ $coin->id }}" name="status">
                            <option value='{{ url("admin/assets/change_status/$coin->id/1") }}' {{ $coin->is_active == 1 ? 'selected' : '' }}>Active
                            </option>
                            <option value='{{ url("admin/assets/change_status/$coin->id/0") }}' {{ $coin->is_active == 0 ? 'selected' : '' }}>In-active
                            </option>

                        </select>
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mb-1">{{ paginateLinks($coins) }}</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
       
    $(document).ready(function() {
        $(".statusactive").change(function() {
            var url = $(this).val()
            if (url) { // require a URL
                window.location = url; // redirect
            }
            return false;
        })


    })
</script>
@endsection