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
                    
                    <p style="color:white;">
                        Invite friends to explore the crypto world with you and <b>get rewards together!</b>
                    </p><br>
                    <h4 style="color:white;">How it works?</h4><br>
                    <ol style="color:white;">
                        <li>1. Sign Up.</li><br>
                        <li>2. Complete Kyc.</li><br>
                        <li>3. Complete bank account addition.</li><br>
                        <li>4. Invite friends by sharing your referal code via whatsapp.</li><br>
                        <li>5. You get rewards when your friend completes registration.</li>
                        
                </div>
            </div>
        </div>
    </div>
    @endsection