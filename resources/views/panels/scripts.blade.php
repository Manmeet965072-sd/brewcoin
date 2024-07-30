<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>

<script src="{{ asset(mix('vendors/bower/jquery.sticky/jquery.sticky.js')) }}"></script>
@yield('vendor-script')

<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>

@if (Request::is('admin**'))
@include('admin.partials.notify')
@else
@include('partials.plugins')
@include('admin.partials.notify')
@endif


<!-- custome scripts file for user -->
<script src="{{ asset(mix('js/core/scripts.js')) }}"></script>
<script>
    document.getElementById("banner").addEventListener("change", readURL, false);

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result).width(150).height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- <script src="{{asset('assets/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('assets/libs/summernote/summernote.min.js')}}"></script>
<script src="{{asset('assets/libs/dropzone/dropzone.min.js')}}"></script> -->

<!-- Page js-->
<!-- <script src="{{asset('assets/js/pages/form-fileuploads.init.js')}}"></script> -->
<!-- <script src="{{asset('assets/js/pages/add-product.init.js')}}"></script> -->
<script src="https://cdn.ckeditor.com/4.15.0/full/ckeditor.js">
</script>


<script type="text/javascript">
    CKEDITOR.replace('product-description2', {
        height: '30em'
    });

    CKEDITOR.replace('product-description1', {
        height: '30em'
    })
</script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->

@yield('page-script')
@stack('script-lib')
@stack('script')
@stack('modals')

<!-- END: Page JS-->
@if (Request::is('admin**') || Request::is('user/profile'))
@livewireScripts
<script defer src="https://unpkg.com/alpinejs@3.7.0/dist/cdn.min.js"></script>
@endif