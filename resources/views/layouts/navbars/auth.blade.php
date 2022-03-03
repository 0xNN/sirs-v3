<div class="sidebar" data-color="black" data-active-color="success">
    <div class="logo">
        <a href="{{ url('/home') }}" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/favicon.png">
            </div>
        </a>
        <a href="{{ url('/home') }}" class="simple-text logo-normal">
            {{ config('myconfig.subname') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            {{-- <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                                        <a href="{{ route('page.index', 'dashboard') }}">
                                            <i class="nc-icon nc-bank"></i>
                                            <p>{{ __('Dashboard') }}</p>
                                        </a>
                                    </li> --}}
            <li class="{{ $elementActive == 'user' || $elementActive == 'profile' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="true" href="#laporancovid">
                    <i class="nc-icon nc-circle-10"></i>
                    <p>
                        {{ __('User') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse show" id="laporancovid">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'profile' ? 'active' : '' }}">
                            <a href="{{ route('profile.edit') }}">
                                <span class="sidebar-mini-icon">{{ __('UP') }}</span>
                                <span class="sidebar-normal">{{ __(' User Profile') }}</span>
                            </a>
                        </li>
                        @if (auth()->user()->role_id == 1)
                        <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                            <a href="{{ route('page.index', 'user') }}">
                                <span class="sidebar-mini-icon">{{ __('U') }}</span>
                                <span class="sidebar-normal">{{ __(' User Management ') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @if (auth()->user()->akses_id == 1)
            <li class="{{ $elementActive == 'covid' ? 'active' : '' }}">
                <a href="{{ route('covid.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.covid') }}</p>
                </a>
            </li>
            @endif
            <li class="{{ $elementActive == 'ruangan' ? 'active' : '' }}">
                <a href="{{ route('ruangan.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.ruangan') }}</p>
                </a>
            </li>
            @if (auth()->user()->akses_id == 1)
            <li class="{{ $elementActive == 'sdm' ? 'active' : '' }}">
                <a href="{{ route('sdm.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.sdm') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'alkes' ? 'active': '' }}">
                <a href="{{ route('alkes.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.alkes') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'pcrnakes' ? 'active': '' }}">
                <a href="{{ route('pcrnakes.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.pcrnakes') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'nakesterinfeksi' ? 'active': '' }}">
                <a href="{{ route('nakesterinfeksi.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.nakesterinfeksi') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'oksigenasi' ? 'active': '' }}">
                <a href="{{ route('oksigenasi.index') }}">
                    <i class="nc-icon nc-single-copy-04"></i>
                    <p>{{ config('myconfig.menu.oksigenasi') }}</p>
                </a>
            </li>
            @endif
            {{-- <li class="{{ $elementActive == 'icons' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'icons') }}">
                    <i class="nc-icon nc-diamond"></i>
                    <p>{{ __('Icons') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'map' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'map') }}">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>{{ __('Maps') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'notifications' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'notifications') }}">
                    <i class="nc-icon nc-bell-55"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'tables' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'tables') }}">
                    <i class="nc-icon nc-tile-56"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'typography' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'typography') }}">
                    <i class="nc-icon nc-caps-small"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li> --}}
        </ul>
    </div>
</div>
