<!-- BEGIN: Vendor CSS-->

  <link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}?{{ time() }}" />

@yield('vendor-style')
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" href="{{ asset(mix('css/core.css')) }}?{{ time() }}" />
<link rel="stylesheet" href="{{ asset(mix('css/base/themes/dark-layout.css')) }}?{{ time() }}" />


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<!-- <link href="{{asset('assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/summernote/summernote.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/libs/dropzone/dropzone.min.css')}}" rel="stylesheet" type="text/css" /> -->

@php $configData = applClasses(); @endphp

<!-- BEGIN: Page CSS-->
  <link rel="stylesheet" href="{{ asset(mix('css/base/core/menu/menu-types/vertical-menu.css')) }}?{{ time() }}" />

{{-- Page Styles --}}
@yield('page-style')

<!-- laravel style -->
<link rel="stylesheet" href="{{ asset(mix('css/overrides.css')) }}?{{ time() }}" />

<!-- BEGIN: Custom CSS-->

  <link rel="stylesheet" href="{{ asset(mix('css/style.css')) }}?{{ time() }}" />

@livewireStyles
