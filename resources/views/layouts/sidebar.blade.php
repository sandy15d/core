<div class="sidebar-container">
    <nav class="sidebar">
        <div class="logo">
            <a href="{{ route('home') }}" draggable="false" style="font-size: 65px">
             {{--  <img src="{{asset('assets/images/logo.png')}}" style="height: 95%" width="210" alt="Core">--}}
Core
            </a>
        </div>

        @include('layouts.menu')
    </nav>
    @include('layouts.footer')
</div>

