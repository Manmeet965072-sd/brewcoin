@extends('layouts/fullLayoutMaster')

@section('title', 'Refer a Friend')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-basic px-2">
    <div class="auth-inner my-2">
        <div class="card" style="background-color:#2E2252;">
            <div class="card-body">


                <div>
                    <h2 style="color:white;">Build your Crypto Tribe</h2><br>
                    <img src="https://assets.stackry.com/LandingPages/refer-content-image.png" style="width:250px;height:250px;">
                    <p style="color:white;">
                        Invite friends to explore the crypto world with you and <b>get rewards together!</b>
                    </p><br>
                    <h4 style="color:white;">How it works?</h4><br>
                    <ol style="color:white;">
                        <li>1. Share referal link.</li><br>
                        <li>2. Friend buys any Crypto.</li><br>
                        <li>3. You get {{getAmount($referral_bonus)}} USD.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @endsection