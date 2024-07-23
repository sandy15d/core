<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Core</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="icon" type="image/x-icon" href="{{URL::to('/')}}assets/css/images/favicon.png"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('upper_style')
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/vendors/data-tables/datatables.min.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/vendors/dropzone/dropzone.min.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/vendors/datetime-flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/vendors/cropperjs/cropper.min.css">
    <link rel="stylesheet" href="{{URL::to('/')}}/assets/css/theme/theme.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    @stack('bottom_style')
    @stack('upper_script')
    <style>
        .ak-form-empty {
            --tw-bg-opacity: 1;
            --tw-text-opacity: 1;
            background-color: rgb(219 234 254 / var(--tw-bg-opacity));
            border-radius: .5rem;
            color: rgb(29 78 216 / var(--tw-text-opacity));
            font-size: .875rem;
            line-height: 1.25rem;
            margin-bottom: 1rem;
            margin-left: .5rem;
            margin-right: .5rem;
            padding: 1rem;
        }
    </style>


</head>

<body>
<div class="layout-container">
    @include('layouts.sidebar')
    <div class="page-container">
        @include('layouts.topbar')
        @include('layouts.header')
        <section class="page-section">
            <div class="page-content js-ak-page-content">
                @yield('content')
            </div>
        </section>
    </div>
</div>

@include('layouts.script')

@include('layouts.toast')
@stack('bottom_script')
</body>

</html>
