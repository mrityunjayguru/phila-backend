<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<base href="{{env('APP_URL')}}">
    <title>@if(isset($page_title) && $page_title != '' ) {{ $page_title.' - '}} @endif {{ config('constants.APP_NAME') }}</title>
	
	<!-- Favicons-->
    <link rel="shortcut icon" href="{{ asset('themeAssets/favicon.png')}}" type="image/x-icon">
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('themeAssets/favicon.png')}}"/>
	
	<link rel="canonical" href="<?php echo"https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>"/>
	<meta name="google-site-verification" content="" />

	<meta name="author" content="{{ config('constants.APP_NAME') }}">
	<meta name="description" content=""/>
	<meta itemprop="name" content=""/>
	<meta itemprop="description" content=""/>
	<meta itemprop="image" content=""/>
	
	<meta name="twitter:card" content="website"/>
	<meta name="twitter:site" content="{{ config('constants.APP_NAME') }}"/>
	<meta name="twitter:title" content=""/>
	<meta name="twitter:description" content=""/>
	<meta name="twitter:creator" content=""/>
	<meta name="twitter:image" content=""/>
	
	<meta property="fb:app_id" content=""/>
	<meta property="og:locale" content="en_US"/>
	<meta property="og:title" content=""/>
	<meta property="og:description" content=""/>
	<meta property="og:type" content="website"/>
	<meta property="og:url" content="<?php echo"https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>"/>
	<meta property="og:image" content=""/>
	<meta property="og:site_name" content=""/>
	
	@include('layouts.theme.partials.head')
	
	<!-- CUSTOM CSS -->
    <link href="{{asset('themeAssets/custom.css')}}" rel="stylesheet">
	@yield('css')
	
	<script>var user_id = ''; @if(Auth::user()) var user_id = '{{ Auth::user()->id }}'; @endif var token = '{{ csrf_token() }}'; var SITE_URL = '{{ url("") }}';</script>
</head>
<body>
	<div class="ps-page">
	@include('layouts.theme.partials.header')
	
	@include('layouts.theme.partials.navigation')
	
	@yield('content')
	</div>
	@include('layouts.theme.partials.footer')
	
	@yield('js')
    <!-- custom js -->
    <script src="{{asset('themeAssets/custom.js')}}"></script>
</body>	
</html>