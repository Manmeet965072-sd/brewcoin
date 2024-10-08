@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Payment Providers</h4>
                    <div class="card-search"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover custom-data-bs-table">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">{{ __('locale.Provider')}}</th>
                                {{-- <th scope="col">{{ __('locale.Supported Currency')}}</th>
                                <th scope="col">{{ __('locale.Enabled Currency')}}</th> --}}
                                <th scope="col">{{ __('locale.Status')}}</th>
                                <th scope="col">{{ __('locale.Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gateways as $k=>$gateway)
                            <tr>
                                <td data-label="{{ __('locale.Provider')}}">
                                    <div class="row centerize">
                                        <div class="col-md-3">
                                            <img src="{{ getImage(imagePath()['gateway']['path'].'/'. $gateway->image,imagePath()['gateway']['size'])}}"
                                                alt="{{ __('locale.image')}}" class="provider-image"></div>
                                        <span class="col-md-9 name">{{__($gateway->name)}}</span>
                                    </div>
                                </td>

                                {{-- <td data-label="{{ __('locale.Supported Currency')}}">
                                    {{ count(json_decode($gateway->supported_currencies,true)) }}
                                </td>
                                <td data-label="{{ __('locale.Enabled Currency')}}">
                                    {{ $gateway->currencies->count() }}
                                </td> --}}

                                <td data-label="{{ __('locale.Status')}}">
                                    @if($gateway->status == 1)
                                    <span class="badge bg-success">{{ __('locale.Active')}}</span>
                                    @else
                                    <span class="badge bg-gray">{{ __('locale.Disabled')}}</span>
                                    @endif

                                </td>
                                <td data-label="{{ __('locale.Action')}}">
                                    <a href="{{ route('admin.payment.provider.edit', $gateway->alias) }}">
                                        <button class="btn btn-sm btn-icon btn-warning editGatewayBtn"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="{{ __('locale.Edit')}}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </a>


                                    @if($gateway->status == 0)
                                    <button data-bs-toggle="modal" data-bs-target="#activateModal"
                                        class="btn btn-sm btn-icon btn-success activateBtn"
                                        data-code="{{$gateway->code}}" data-name="{{__($gateway->name)}}"
                                        data-original-title="{{ __('locale.Enable')}}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @else
                                    <button data-bs-toggle="modal" data-bs-target="#deactivateModal"
                                        class="btn btn-sm btn-icon btn-danger deactivateBtn"
                                        data-code="{{$gateway->code}}" data-name="{{__($gateway->name)}}"
                                        data-original-title="{{ __('locale.Disable')}}">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>

            </div><!-- card end -->
            <div class="mb-1">
                {{ paginateLinks($gateways) }}
            </div>
        </div>


    </div>



    {{-- ACTIVATE METHOD MODAL --}}
    <div id="activateModal" class="modal fade text-start" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('locale.Payment Method Activation Confirmation')}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.payment.provider.activate')}}" method="POST">
                    @csrf
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>{{ __('locale.Are you sure to activate')}} <span class="fw-bold method-name"></span> {{ __('locale.method')}}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">{{ __('locale.Close')}}</button>

                        <button type="submit" class="btn btn-primary">{{ __('locale.Activate')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DEACTIVATE METHOD MODAL --}}
    <div id="deactivateModal" class="modal fade text-start" tabindex="-1" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('locale.Payment Method Disable Confirmation')}}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('admin.payment.provider.deactivate')}}" method="POST">
                    @csrf
                    <input type="hidden" name="code">
                    <div class="modal-body">
                        <p>{{ __('locale.Are you sure to disable')}} <span class="fw-bold method-name"></span> {{ __('locale.method')}}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">{{ __('locale.Close')}}</button>
                        <button type="submit" class="btn btn-danger">{{ __('locale.Disable')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict"
        $(document).on('click','.activateBtn',function () {
            var modal = $('#activateModal');
            modal.find('.method-name').text($(this).data('name'));
            modal.find('input[name=code]').val($(this).data('code'));
        });

        $(document).on('click','.deactivateBtn',function () {
            var modal = $('#deactivateModal');
            modal.find('.method-name').text($(this).data('name'));
            modal.find('input[name=code]').val($(this).data('code'));
        });

    </script>
@endpush
