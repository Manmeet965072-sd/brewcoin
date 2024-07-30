@extends('layouts.app', ['title' => 'Translations'])
@section('content')
<!-- Start Content-->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Translation Manager</h4>
        {{--<div class="card-search"></div>--}}
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                {{-- <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i class="mdi mdi-cog"></i></button> --}}
                                <a href="{{ route('admin.translations.open-import-form') }}" class="btn btn-danger waves-effect waves-light open-modal"><i class="fe-upload" aria-hidden="true"></i> Import</a>
                            </div>
                        </div><!-- end col-->
                        <div class="col-lg-6">
                            <div class="text-lg-right mt-3 mt-lg-0">
                                {{-- <button type="button" class="btn btn-success waves-effect waves-light mr-1"><i class="mdi mdi-cog"></i></button> --}}
                                <a href="{{route('admin.translations.export')}}" class="btn btn-danger waves-effect waves-light"><i class="mdi mdi-plus-circle mr-1"></i> Export</a>
                            </div>
                        </div><!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end card-box -->
            </div> <!-- end col-->
        </div>
    </div>
    <div class="card-body">
        @if($translations->total() > 0)
        <div class="table-responsive" style="min-height: 80vh">
            <table class="table table-hover custom-data-bs-table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Label</th>
                        @foreach($languages as $language)
                        <th scope="col">{{$language->name}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($translations as $translation)
                    <tr>
                        <td data-label="User">
                            <span class="sub sub-s2 sub-dtype">{{ $translation->name }}</span>
                        </td>
                        @foreach($translation->translations as $language)
                        <td data-label="Doc Type">
                            <span class="sub sub-s2 sub-dtype">{{ $language->value }}</span>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-light text-center rounded py-2">
            <p><i class="bi bi-arrow-down"></i><br>{{'No Translation found!' }}</p>
        </div>
        @endif
        <div class="mb-1">{{ paginateLinks($translations) }}</div>
    </div>
</div>

<script>

</script>
@endsection