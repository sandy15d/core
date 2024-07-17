<div class="page-top-bar">
    <div class="left-side">
        <div class="icon-container js-ak-sidebar-toggle">
            <div class="icon">
                <div class="font-awesome-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        fit="" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                        focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="right-side">

        <div class="sidebar-user">
            <div class="sidebar-user-image">
                {{-- <img src="data:image/jpeg;base64,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"
                    draggable="false" /> --}}
                <img src="https://eu.ui-avatars.com/api/?name={{ Auth::user()->name }}&background=A585A3&color=fff&bold=true&length=1&font-size=0.5"
                    alt="">
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
            </div>
            <div class="sidebar-user-links">
                <a draggable="false" class="link" href="{{ route('my-account') }}">
                    <div class="icon">
                        <div class="font-awesome-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <path
                                    d="M224 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 224 256zM274.7 304H173.3C77.61 304 0 381.6 0 477.3c0 19.14 15.52 34.67 34.66 34.67h378.7C432.5 512 448 496.5 448 477.3C448 381.6 370.4 304 274.7 304z" />
                            </svg>
                        </div>
                    </div>
                    <div class="title">My Account</div>
                </a>
                <a draggable="false" class="link" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="icon">
                        <div class="font-awesome-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M288 256C288 273.7 273.7 288 256 288C238.3 288 224 273.7 224 256V32C224 14.33 238.3 0 256 0C273.7 0 288 14.33 288 32V256zM80 256C80 353.2 158.8 432 256 432C353.2 432 432 353.2 432 256C432 201.6 407.3 152.9 368.5 120.6C354.9 109.3 353 89.13 364.3 75.54C375.6 61.95 395.8 60.1 409.4 71.4C462.2 115.4 496 181.8 496 255.1C496 388.5 388.5 496 256 496C123.5 496 16 388.5 16 255.1C16 181.8 49.75 115.4 102.6 71.4C116.2 60.1 136.4 61.95 147.7 75.54C158.1 89.13 157.1 109.3 143.5 120.6C104.7 152.9 80 201.6 80 256z" />
                            </svg>
                        </div>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <div class="title">Sign out</div>
                </a>
            </div>
        </div>
    </div>
</div>
