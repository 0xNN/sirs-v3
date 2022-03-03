<div class="wrapper">

    @include('layouts.navbars.auth')

    <div class="main-panel">
        @include('layouts.navbars.navs.auth')
        {{-- <div id="app"> --}}
        @yield('content')
        {{-- </div> --}}
        @include('layouts.footer')
    </div>
</div>