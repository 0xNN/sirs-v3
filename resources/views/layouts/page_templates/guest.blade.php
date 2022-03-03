@include('layouts.navbars.navs.guest')

<div class="wrapper wrapper-full-page ">
    <div class="full-page section-image" filter-color="black" data-image="{{ asset('paper') . '/' . ($backgroundImagePath ?? "img/img-1-1000x600.jpg") }}">
        @yield('content')
        @include('layouts.footer')
    </div>
</div>
