@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <h1 class="admin-h1 mb-5 flex items-center">
                    <a href="{{ url('/admin/members') }}" title="Back" class="rounded-full bg-gray-100 p-2">
                        <img src="{{ url('uploads/icons/back.svg') }}" class="w-3 h-3">
                    </a>
                <span class="mx-3">Print Membership Card</span>
            </h1>

            <div class="relative flex items-center w-1/2 lg:justify-end">
                <div class="flex items-center">
                    <a href="{{ url('/admin/membershipCard/download/member') }}">        
                        <h2 class="bg-blue-500 text-white rounded px-3 py-1 mr-10">Print Member</h2>
                    </a> 

                    
                </div>
            </div>
        </div>
        @include('partials.message')
        <div style="display: flex;flex-wrap: wrap;">
        @foreach($users as $user)
        <div style="width: 50%;padding: 10px;">

            <div
      class="mr-auto my-8 border-2 border-gray-500 rounded-lg overflow-hidden bg-gray-200"
    >
      <div class="flex ">
        <!-- Left Section: Info -->
        <div
          class="w-3/5 bg-cover p-4" style="width:100%">

        <!-- Front Side -->
    <div class="card">
      <!-- Logo + QR Row -->
     

      <!-- Logo + QR Row -->
      <table style="width: 100%; padding-right: 0; padding-left: 0;">
        <tr style="padding-right:10px;">
          <td style="padding-right:10px;">

             @if($user->userprofile->AvatarPath)
          <img
            class="w-32 h-32 border-4 border-white"
            src="{{ $user->userprofile->AvatarPath}}"
            alt="Profile Picture" style="height: 120px; margin: 0"
          />
          @else
          <img
            class="w-32 h-32 border-4 border-white"
            src="{{ url('images/default-user.png') }}"
            alt="Profile Picture" style="height: 120px; margin: 0">

          @endif
            <!-- <img style="height: 120px; margin: 0" src="{{ url('images/logo.png') }}" /> -->
          </td>
           
          <td style="padding-right: 10px;">
        @if (Auth::user()->ChurchLogo['meta_value'] != '-')
                    <img src="{{ Auth::user()->ChurchLogoPath }}" style="height:55px;"  class="w-32 h-32 border-4 border-white" alt="Logo" style="height: 120px; margin: 0">
                
        @else
        <img src="{{ asset('images/church_cms_logo.jpg') }}" style="height:55px;"  class="w-32 h-32 border-4 border-white" alt="Logo" style="height: 120px; margin: 0">
        @endif
      </td>

          <td style="">

              @php
$from = [255, 0, 0];
$to = [0, 0, 255];

                             $url=url('/admin/attandance/'.$user->name);
                                    @endphp


             <img src="data:image/png;base64, {!! base64_encode(QrCode::eye('square')->format('png')
    ->eye('circle')
    ->color(0, 0, 0)  
    ->margin(1)
    ->generate($url)) !!} " class="" style="width: 120px; height: auto;margin-left:auto;">
          <!--  <div class="qr">
              <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=example.com"
                style="width: 120px; height: auto"
              />
            </div> -->
          </td>

         

        </tr>
      </table>

      <!-- Profile + Details Row -->
      <table style="width: 100%">
        <tr>
          <td style="padding-top: 10px; text-align: left">
            <p
              style="margin-bottom: 0em !important; margin-top: 0em !important"
            >
              <span style="font-weight: 600">NAME -</span> {{ strtoupper($user->FullName) }}
            </p>
          </td>
        </tr>
      </table>
      <table style="width: 100%">
        <tr>
          <td style="text-align: left">
            <p
              style="margin-top: 2px !important; margin-bottom: 0px !important"
            >
              <span style="font-weight: 600">ID NUMBER -</span>
               #{{ $user->id }}
            </p>
          </td>
          <td style="padding-left: 16px; vertical-align: top">
            <p
              style="
                font-weight: 500;
                margin-top: 5px !important;
                margin-bottom: 0px !important;
                font-size: 15px;
              "
            >
              <span style="font-weight: 600">PHONE -</span>
              <span style="margin-left: 3px; border-bottom: 1px solid black"
                >{{ $user->mobile_no }}</span
              >
            </p>
          </td>
        </tr>
      </table>
      <table style="width: 100%;margin-top: 5px;">
       
        <tr>
          <td style=" text-align: left">
            <p
              style="margin-bottom: 0em !important; margin-top: 2px !important"
            >
              <span style="font-weight: 600">ADDRESS -</span> <span style="font-size:10px;"> {{ strtoupper($user->userprofile->address) }},{{strtoupper($user->userprofile->city->name)}} {{strtoupper($user->userprofile->state->name)}} ,{{strtoupper($user->userprofile->country->name)}}</span>
            </p>
             <p
              style="margin-bottom: 0em !important; margin-top: 5px !important"
            >

            @php
            $currentYear = date('Y');
          $nextYear = date('Y', strtotime('+1 year'));

           $year=$currentYear . ' - ' . $nextYear;


            @endphp
              <span style="font-weight: 600">Year :</span> <span style="font-size:10px;">{{$year}}</span>
            </p>
          </td>
        </tr>
      </table>
      <!-- Website Row -->
    </div>


    </div>
</div>
</div>
</div>
         
        @endforeach
         </div>
    </div>
@endsection