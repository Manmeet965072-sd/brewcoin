@extends('layouts/fullLayoutMaster')

@section('title', 'Complete Kyc')

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
                    <h2>Why do you need to Add kyc Details?</h2><br>
                    <img src="https://i0.wp.com/getscann.com/wp-content/uploads/2020/11/KYC.png?resize=1080%2C588&ssl=1" style="width:450px;height:280px;">
                    <p><br><br>
                        Kyc is required to verify your authenticity and maintain security standards before you invest in cryptocurrencies.KYC process is the process of verifying the identity of a customer or client. This can be done through the use of government-issued identification documents, such as a passport or driver’s license, or by other means, such as utility bills or bank statements. The goal of KYC is to ensure that the customer or client is who they say they are, and to prevent money laundering and other illicit activities.
                    </p>
                    <p><br><br>
                        From a business perspective, implementing KYC processes can help crypto companies by protecting them against fraud and money laundering. This is especially important in crypto, where scams are not that rare. It can also help businesses to build trust with their customers or clients, as it shows that the business is taking steps to verify the identity of those who are using its services.
                    </p>
                    <p><br><br>
                        From a customer or client perspective, know-your-customer can help to protect their crypto assets against fraud. It can also make it easier for customers or clients to do business with a company, as they will not need to provide their personal information each time they interact with the company.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection