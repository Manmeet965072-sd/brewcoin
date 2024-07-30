@extends('layouts.app')
@section('content')
<div class="row mb-none-30">
    <div class="col-lg-7 col-md-7">
        <div class="card">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden"name="id" value="{{isset($bannerDetail->id) ? $bannerDetail->id: ''}}">

                    <div class="row mb-3">
                        <div class="col-md-12 ">
                            <label class="form-control-label h6">Banner title
                            </label>
                            <input class="form-control form-control-lg" type="text" placeholder="Title" name="title" value="{{isset($bannerDetail->title) ? $bannerDetail->title: ''}}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-control-label  h6 mt-1"> Description</label>
                            <textarea name="description" rows="3" class="form-control"
                                placeholder="Description"
                                required>{{isset($bannerDetail->description) ? $bannerDetail->description: ''}}</textarea>
                        </div>
                     
                        <div class="col-md-12">
                            <br/>
                            <input type="file" name="image" class="form-control" id="image" onchange="loadFile(event)"
                                accept=".png, .jpg, .jpeg" />
                        </div>
                        <div class="col-md-12">
                            <br/>
                            <img class="img-thumbnail mb-1" id="output_image"
                                src="{{isset($bannerDetail->banner_url) ? getImage(imagePath()['template']['path'].'/'. $bannerDetail->banner_url,imagePath()['profileImage']['size']) : getImage(imagePath()['gateway']['path'],imagePath()['gateway']['size'])}}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2 btn-lg">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
  var loadFile = function(event) {
    var output = document.getElementById('output_image');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
@endsection

@push('style')
<style type="text/css">
    .logoPrev{
        background-size: 100%;
    }
    .iconPrev{
        background-size: 100%;
    }
</style>
@endpush
