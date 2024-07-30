@extends('layouts/fullLayoutMaster')

@section('title', 'Add bank account')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/base/pages/authentication.css')) }}">
@endsection

@section('content')
<div class="auth-wrapper auth-basic px-2">
    <div class="auth-inner my-2">
        <div class="card">
            <div class="card-body">


                <div>
                    <h2>Why do you need to Add Bank Account?</h2><br>
                    <img src="https://imgnew.outlookindia.com/public/uploads/articles/2021/9/30/dcd8369573ac3185f1f4d3da76b252b42.jpg" style="width:330px;height:190px;">
                    <p><br><br>
                        You have to open a trading account at the Crypto exchange of your choice. This account will be similar to that in a bank. While registering, the exchange will verify your credentials based on the services you opt for, the amount you plan to invest, and the available coins trading options.Your bank account details would be sent to the administrator whenever you want to withdraw money from your bank account.The admin will verify your bank account and make the withdraw transaction within 24 hours.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection