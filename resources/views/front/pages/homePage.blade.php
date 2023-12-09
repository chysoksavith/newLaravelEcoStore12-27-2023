@extends('front.layouts.app')
@section('content')
    <section class="FirstSection">
        <div class="WrapperVdo">
            <div class="vdoA">
                <video class="video" autoplay loop muted>
                    <source src="{{ asset('FearofGod.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="title">
                <span class="MainTotle">ATHLETICS</span>
                <span class="titleShop">
                    <a href="{{ route('front.home') }}">SHOP</a>
                </span>
            </div>
        </div>
    </section>
    <div class="MainContain">
        <div class="wrapper-container">
            <div class="containerBrand">
                <div class="wrap-img">
                    <div class="BraImg">
                        <img src="https://www.designscene.net/wp-content/uploads/2023/11/Fear-of-God-Athletics-2023-14.jpg"
                            alt="">
                    </div>
                    <div class="relativeTitle">
                        <span>FEAR OF GOD</span>
                    </div>
                </div>
            </div>
            <div class="containerBrand">
                <div class="wrap-img">
                    <div class="BraImg">
                        <img src="https://www.designscene.net/wp-content/uploads/2023/11/Fear-of-God-Athletics-2023-14.jpg"
                            alt="">
                    </div>
                    <div class="relativeTitle">
                        <span>FEAR OF GOD</span>
                    </div>
                </div>
            </div>
            <div class="containerBrand">
                <div class="wrap-img">
                    <div class="BraImg">
                        <img src="https://www.designscene.net/wp-content/uploads/2023/11/Fear-of-God-Athletics-2023-14.jpg"
                            alt="">
                    </div>
                    <div class="relativeTitle">
                        <span>FEAR OF GOD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
