<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $dark_mode ? 'dark' : '' }}">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="{{ asset('dist/images/logo.svg') }}" rel="shortcut icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="IT-Portal Eps Genco-3 Tiếp nhận và xử lý yêu cầu.">
    <meta name="keywords" content="IT-Portal Eps Genco-3 Tiếp nhận và xử lý yêu cầu">
    <meta name="author" content="EPS-Genco3">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @yield('head')

    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{url('/dist/css/app.css')}}" />
    <!-- END: CSS Assets-->
</head>
<!-- END: Head -->
    
@yield('body')

</html>