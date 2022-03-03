<footer class="footer footer-black  footer-white ">
    <div class="container-fluid">
        <div class="row">
            {{-- <nav class="footer-nav">
                <ul>
                    <li>
                        <a href="" target="_blank">{{ __('') }}</a>
                    </li>
                    <li>
                        <a href="" target="_blank">{{ __('') }}</a>
                    </li>
                    <li>
                        <a href="" target="_blank">{{ __('') }}</a>
                    </li>
                    <li>
                        <a href="" target="_blank">{{ __('') }}</a>
                    </li>
                </ul>
            </nav> --}}
            <div class="credits ml-auto">
                <span class="copyright">
                    ©
                    <script>
                        document.write(new Date().getFullYear())
                    </script>{{ __(', Powered by ') }}<a class="@if(Auth::guest()) text-white @endif" href="http://rsud.sumselprov.go.id" target="_blank">{{ __('RSUD Siti Fatimah') }}</a>{{ __(' and ') }}<a class="@if(Auth::guest()) text-white @endif" href="javascript:void(0)">{{ __('IT Team') }}</a>
                </span>
            </div>
        </div>
    </div>
</footer>