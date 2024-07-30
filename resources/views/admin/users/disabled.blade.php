@extends('layouts.app')
@section('content')
<div class="row" id="table-hover-row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Users</h4>
                <div class="card-search"></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('locale.User')}}</th>
                            {{--<th>{{ __('locale.Username')}}</th>--}}
                            <th>{{ __('locale.Email')}}</th>

                            <th>{{ __('locale.Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        @if($user->id != 1)
                        <tr>
                            <td data-label="{{ __('locale.User')}}">
                                <div class="row centerize">
                                    <div class="col-md-3 thumb">
                                        <img src="{{ getImage(imagePath()['profileImage']['path'].'/'. $user->profile_photo_path,imagePath()['profileImage']['size']) }}" alt="{{ __('locale.image')}}">
                                    </div>
                                    <span class="col-md-9 name">{{$user->fullname}}</span>
                                </div>
                            </td>
                            {{--<td data-label="{{ __('locale.Username')}}"><a href="{{ route('admin.users.detail', $user->id) }}">{{ $user->username }}</a>
                            </td>--}}
                            <td data-label="{{ __('locale.Email')}}">{{ $user->email }}</td>

                            <td data-label="{{ __('locale.Action')}}">

                                <a href="{{route('admin.users.enable', $user->id)}}" data-id="{{ $user->id }}" class="btn btn-icon btn-info btn-sm removeModalBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('locale.Enable')}}">
                                    <i class="bi bi-arrow-repeat"></i>
                                </a>
                                {{--<a href="{{ route('admin.users.detail', $user->id) }}">
                                <button class="btn btn-icon btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('locale.Details')}}">
                                    <i class="bi bi-display"></i>
                                </button>
                                </a>--}}
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-1">{{ paginateLinks($users) }}</div>
    </div>
</div>


@endsection
@push('breadcrumb-plugins')
<div class="d-flex flex-row-reverse">
    <div class="col-md-8 col-sm-12">
        <form action="{{ route('admin.users.search', $scope ?? str_replace('admin.users.', '', request()->route()->getName())) }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="@lang('Search ...')" value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>
@endpush
@push('script')
<script>
    $(function() {
        "use strict";
        $('.removeModalBtn').on('click', function() {
            $('#removeModal').find('input[name=id]').val($(this).data('id'));
            $('#removeModal').modal('show');
        });
    });
</script>
@endpush