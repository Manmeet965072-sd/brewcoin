@extends('layouts.app')
@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2"><a class="btn  btn-primary waves-effect waves-float waves-light" href="{{route('admin.setting.createBanner')}}">Add banner</a></div> 
        </div><br/>
        <div class="row">
            @if (count($banners) > 0)
                @foreach ($banners as $banner)
                <div class="col-md-4">
                    <div class="card card-01">
                        <img class="card-img-top" src="{{ getImage(imagePath()['template']['path'].'/'. $banner->banner_url,imagePath()['profileImage']['size']) }}" alt="Card image cap" />
                        <div class="card-body">
                            <span class="badge-box"><i class="fa fa-check"></i></span>
                            <h4 class="card-title">{{$banner->title}}</h4>
                            <p class="card-text">{{$banner->description}}</p>
                            <a href="{{route('admin.setting.deleteBanner',$banner->id)}}" class="btn btn-danger text-uppercase">Delete</a>
                            <a class="btn  btn-primary" href="{{route('admin.setting.editBanner',$banner->id)}}">Edit</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <br/>
            <p class="h1 text-center">
                Banner not found
            </p>
            @endif
        </div>
    </div>
</section>

@endsection