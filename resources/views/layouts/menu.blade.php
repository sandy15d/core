<div class="menu-list">
    <div class="item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a draggable="false" class="link" href="{{ route('home') }}">
            @includeIf('layouts.icons.home_icon')
            <div class="title">Home</div>
        </a>
    </div>
    @canany(['page-builder','menu-builder'])
        <div class="dropdown js-ak-dropdown">
            <div class="dropdown-item js-ak-dropdown-item">
                <div class="item">
                    <a draggable="false" class="link" href="">
                        @includeIf("layouts.icons.setting_icon")
                        <div class="title">Master</div>
                        <div class="icon">
                            <div class="font-awesome-icon">
                                <svg focusable="false" data-prefix="fas" data-icon="angle-right"
                                     class="svg-inline--fa fa-angle-right" role="img" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 256 512">
                                    <path fill="currentColor"
                                          d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div
                class="dropdown-container {{ request()->routeIs('page-builder.*') || request()->routeIs('mapping-builder.*') || request()->routeIs('menu-builder.*') || request()->routeIs('project.*') || request()->routeIs('api-builder.*')? 'show' : '' }}">
                <div class="dropdown-menu-list">
                    @can('page-builder')
                        <div class="item {{ request()->routeIs('page-builder.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('page-builder.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Page Builder</div>
                            </a>
                        </div>
                        <div class="item {{ request()->routeIs('mapping-builder.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('mapping-builder.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Mapping Builder</div>
                            </a>
                        </div>
                        <div class="item {{ request()->routeIs('api-builder.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('api-builder.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">API Builder</div>
                            </a>
                        </div>
                        <div class="item {{ request()->routeIs('project.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('project.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Project Builder</div>
                            </a>
                        </div>
                    @endcan
                    @can('menu-builder')
                        <div class="item {{ request()->routeIs('menu-builder.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('menu-builder.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Menu Builder</div>
                            </a>
                        </div>
                    @endcan
                    @can('import-excel')
                        <div class="item {{ request()->routeIs('import.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('import.form') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Import Data</div>
                            </a>
                        </div>
                    @endcan
                </div>
            </div>

        </div>
    @endcanany
    @canany(['list-user','list-role','list-permission'])
        <div class="dropdown js-ak-dropdown">
            <div class="dropdown-item js-ak-dropdown-item">
                <div class="item">
                    <a draggable="false" class="link" href="">
                        @includeIf("layouts.icons.user_access")
                        <div class="title">User Access</div>
                        <div class="icon">
                            <div class="font-awesome-icon">
                                <svg focusable="false" data-prefix="fas" data-icon="angle-right"
                                     class="svg-inline--fa fa-angle-right" role="img" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 256 512">
                                    <path fill="currentColor"
                                          d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div
                class="dropdown-container {{ request()->routeIs('user.*') || request()->routeIs('role.*') || request()->routeIs('permission.*') ? 'show' : '' }}">
                <div class="dropdown-menu-list">
                    @can('list-user')
                        <div class="item {{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('user.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">User</div>
                            </a>
                        </div>
                    @endcan
                    @can('list-role')
                        <div class="item {{ request()->routeIs('role.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('role.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Roles</div>
                            </a>
                        </div>
                    @endcan
                    @can('list-permission')
                        <div class="item {{ request()->routeIs('permission.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route('permission.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">Permission</div>
                            </a>
                        </div>
                    @endcan

                </div>
            </div>

        </div>
    @endcanany
    @php
        $menus = getMenuList();

    @endphp

    @if(isset($menus))
        @foreach($menus as $menu)
            @can($menu->permissions)
                @if(empty($menu->children))
                    @if($menu->menu_url =='#')
                        <div class="item">
                            <a draggable="false" class="link" href="javascript:void(0);">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">{{ $menu->menu_name }}</div>
                            </a>
                        </div>
                    @else
                        <div class="item {{ request()->routeIs($menu->menu_url.'.*') ? 'active' : '' }}">
                            <a draggable="false" class="link" href="{{ route($menu->menu_url.'.index') }}">
                                @includeIf("layouts.icons.page_icon")
                                <div class="title">{{ $menu->menu_name }}</div>
                            </a>
                        </div>
                    @endif

                @else
                    @php
                        $childPermissions = collect($menu->children)->pluck('permissions')->toArray();
                        $activeChild = collect($menu->children)->contains(function ($child) {
                        return request()->routeIs($child['menu_url'].'.*');
                    });
                    @endphp
                    @canany($childPermissions)
                        <div class="dropdown js-ak-dropdown">
                            <div class="dropdown-item js-ak-dropdown-item">
                                <div class="item">
                                    <a draggable="false" class="link" href="#">
                                        @includeIf("layouts.icons.folder_icon")
                                        <div class="title">{{ $menu->menu_name }}</div>
                                        <div class="icon">
                                            <div class="font-awesome-icon">
                                                <svg focusable="false" data-prefix="fas" data-icon="angle-right"
                                                     class="svg-inline--fa fa-angle-right" role="img"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     viewBox="0 0 256 512">
                                                    <path fill="currentColor"
                                                          d="M64 448c-8.188 0-16.38-3.125-22.62-9.375c-12.5-12.5-12.5-32.75 0-45.25L178.8 256L41.38 118.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0l160 160c12.5 12.5 12.5 32.75 0 45.25l-160 160C80.38 444.9 72.19 448 64 448z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown-container {{ $activeChild ? 'show' : '' }}">
                                <div class="dropdown-menu-list">
                                    @foreach($menu->children as $child)
                                        @can([$child->permissions])
                                            <div
                                                class="item {{ request()->routeIs($child['menu_url'].'.*') ? 'active' : '' }}">
                                                <a draggable="false" class="link"
                                                   href="{{ route($child->menu_url.'.index') }}">
                                                    <div class="icon">
                                                        <div class="font-awesome-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 viewBox="0 0 384 512">
                                                                <path
                                                                    d="M64 464c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm56 256c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24H264c13.3 0 24-10.7 24-24s-10.7-24-24-24H120z"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="title">{{ $child->menu_name }}</div>
                                                </a>
                                            </div>
                                        @endcan
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endcanany
                @endif
            @endcan
        @endforeach
    @endif


</div>
