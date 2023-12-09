<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- cdn icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
    {{-- style css --}}
    <link rel="stylesheet" href="{{ asset('front-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front-assets/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('front-assets/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('front-assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('front-assets/css/homePages.css') }}">
    <title>EcoStore</title>
</head>

<body>

    @include('front.layouts.header')

    @yield('content')

    {{-- @include('front.layouts.footer') --}}

    @yield('scripts')

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
    {{-- ajax  --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>

</body>

</html>
