<!-- Logo -->
{{-- <a href="{{route('user.list')}}" class="logo">
<!-- mini logo for sidebar mini 50x50 pixels -->
<span class="logo-mini"><b></b></span>
<!-- logo for regular state and mobile devices -->
<span class="logo-lg"><b></b></span>
</a> --}}
<!-- Header Navbar: style can be found in header.less -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Sidebar toggle button-->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        @php
            $arrMall = [
                'biz' => 'Honten B',
                'df'  => 'Honten C',
            ];
            $otherMall = (session('mall') == 'biz') ? 'df': 'biz';
        @endphp
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs"><i class="fas fa-random"></i> {{ $arrMall[session('mall')] ?? '' }}</span>
            </a>
            <ul class="dropdown-menu">
                <li class="user-footer">
                    <a href="{{ route('switch.mall', ['mall' => $otherMall]) }}" class="btn btn-default btn-flat"> {{  $arrMall[$otherMall] ?? '' }}</a>
                </li>
            </ul>
        </li>

        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs">{{Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
                <li class="user-footer">
                    <a href="{{route('user.edit', ['id' => Auth::user()->id])}}" class="btn btn-default btn-flat"><i class="fas fa-user-edit"></i> Edit infor</a>
                </li>
                <li class="user-footer">
                    <a href="login/logout" class="btn btn-default btn-flat"><i class="fas fa-door-open"></i> Sign
                        out</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
