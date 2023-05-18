@extends('../layout/main')

@section('head')
    @yield('subhead')
@endsection

@section('content')
    @include('../layout/components/mobile-menu')
    @include('../layout/components/top-bar')
    <div class="wrapper">
        <div class="wrapper-box">
            <!-- BEGIN: Side Menu -->
            <nav class="side-nav">
                <ul>
                    @foreach ($side_menu as $menuKey => $menu)
                        @if ( in_array($userInfo->role, $menu['role']) ) 
                            @if ($menu['devider'])
                                <li class="side-nav__devider my-6"></li>
                            @endif
                            <li>
                                <a href="{{ isset($menu['route_name']) ? route($menu['route_name']) : 'javascript:;' }}" class="{{ $first_level_active_index == $menuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                    <div class="side-menu__icon">
                                        <i data-feather="{{ $menu['icon'] }}"></i>
                                    </div>
                                    <div class="side-menu__title">
                                        {{ $menu['title'] }}
                                        @if (isset($menu['sub_menu']))
                                            <div class="side-menu__sub-icon">
                                                <i data-feather="chevron-down"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                @if (isset($menu['sub_menu']))
                                    <ul class="{{ $first_level_active_index == $menuKey ? 'side-menu__sub-open' : '' }}">
                                        @foreach ($menu['sub_menu'] as $subMenuKey => $subMenu)
                                            @if ( in_array($userInfo->role, $subMenu['role']))
                                                <li class="border-t border-theme-17">
                                                    <a href="{{ isset($subMenu['route_name']) ? route($subMenu['route_name']) : 'javascript:;' }}" class="{{ $second_level_active_index == $subMenuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                                        <div class="side-menu__icon">
                                                            <i data-feather="{{$subMenu['icon']}}"></i>
                                                        </div>
                                                        <div class="side-menu__title">
                                                            {{ $subMenu['title'] }}
                                                            @if (isset($subMenu['sub_menu']))
                                                                <div class="side-menu__sub-icon">
                                                                    <i data-feather="chevron-down"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    @if (isset($subMenu['sub_menu']))
                                                        <ul class="{{ $second_level_active_index == $subMenuKey ? 'side-menu__sub-open' : '' }}">
                                                            @foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu)
                                                                <li class="border-t border-theme-16">
                                                                    <a href="{{ isset($lastSubMenu['route_name']) ? route($lastSubMenu['route_name']) : 'javascript:;' }}" class="{{ $third_level_active_index == $lastSubMenuKey ? 'side-menu side-menu--active' : 'side-menu' }}">
                                                                        <div class="side-menu__icon">
                                                                            <i data-feather="{{$lastSubMenu['icon']}}"></i>
                                                                        </div>
                                                                        <div class="side-menu__title">{{ $lastSubMenu['title'] }}</div>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
            <!-- END: Side Menu -->
            <!-- BEGIN: Content -->
            <div class="content">
                @yield('subcontent')
            </div>
            <!-- END: Content -->
        </div>
    </div>
@endsection