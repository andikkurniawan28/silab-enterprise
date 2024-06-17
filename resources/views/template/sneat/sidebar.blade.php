<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if(isset($setup->company_logo) && $setup->company_logo)
                    <img src="{{ asset($setup->company_logo) }}" alt="Company Logo" style="height: 25px;">
                @endif
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ ucwords(str_replace('_', ' ', $setup->app_name)) }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $permissions = collect($setup->permission)
            ->pluck('feature.route')
            ->toArray();
    @endphp

    <ul class="menu-inner py-1">

        <li class="menu-item @yield('dashboard-active')">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'dashboard')) }}</div>
            </a>
        </li>

        @if (in_array('setup.index', $permissions))
            <li class="menu-item @yield('setup-active')">
                <a href="{{ route('setup.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'setup')) }}</div>
                </a>
            </li>
        @endif

        @if (in_array('role.index', $permissions) ||
                in_array('user.index', $permissions) ||
                in_array('activity_log', $permissions))
            <li
                class="menu-item
                @yield('role-active')
                @yield('user-active')
                @yield('activity_log-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-door-open"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'access')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('role.index', $permissions))
                        <li class="menu-item @yield('role-active')">
                            <a href="{{ route('role.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'role')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('user.index', $permissions))
                        <li class="menu-item @yield('user-active')">
                            <a href="{{ route('user.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'user')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('activity_log', $permissions))
                        <li class="menu-item @yield('activity_log-active')">
                            <a href="{{ route('activity_log') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'activity_log')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('station.index', $permissions) ||
                in_array('material_category.index', $permissions) ||
                in_array('measurement_unit.index', $permissions) ||
                in_array('option', $permissions) ||
                in_array('parameter', $permissions) ||
                in_array('material', $permissions) ||
                in_array('report_type', $permissions))
            <li
                class="menu-item
                @yield('station-active')
                @yield('material_category-active')
                @yield('measurement_unit-active')
                @yield('option-active')
                @yield('parameter-active')
                @yield('material-active')
                @yield('report_type-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-data"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('station.index', $permissions))
                        <li class="menu-item @yield('station-active')">
                            <a href="{{ route('station.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'station')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('material_category.index', $permissions))
                        <li class="menu-item @yield('material_category-active')">
                            <a href="{{ route('material_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">
                                    {{ ucwords(str_replace('_', ' ', 'material_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('measurement_unit.index', $permissions))
                        <li class="menu-item @yield('measurement_unit-active')">
                            <a href="{{ route('measurement_unit.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'measurement_unit')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('option.index', $permissions))
                        <li class="menu-item @yield('option-active')">
                            <a href="{{ route('option.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'option')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('parameter.index', $permissions))
                        <li class="menu-item @yield('parameter-active')">
                            <a href="{{ route('parameter.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'parameter')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('material.index', $permissions))
                        <li class="menu-item @yield('material-active')">
                            <a href="{{ route('material.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'material')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('report_type.index', $permissions))
                        <li class="menu-item @yield('report_type-active')">
                            <a href="{{ route('report_type.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'report_type')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('analysis.index', $permissions) ||
            in_array('monitoring.index', $permissions))
            <li class="menu-item
                @yield('analysis-active')
                @yield('monitoring-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-transfer"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'transaction')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('analysis.index', $permissions))
                        <li class="menu-item @yield('analysis-active')">
                            <a href="{{ route('analysis.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'analysis')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('monitoring.index', $permissions))
                        <li class="menu-item @yield('monitoring-active')">
                            <a href="{{ route('monitoring.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'monitoring')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (in_array('result_by_station.index', $permissions))
            <li class="menu-item @yield('result_by_station-active')">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-station"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'result_by_station')) }}</div>
                </a>
                <ul class="menu-sub">
                    @foreach ($setup->stations as $station)
                        <li class="menu-item @yield("result_by_station_id_{$station->id}-active")">
                            <a href="{{ route('result_by_station.index', $station->id) }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', $station->name)) }}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        @if (in_array('result_by_material_category.index', $permissions))
            <li class="menu-item @yield('result_by_material_category-active')">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'result_by_material_category')) }}
                    </div>
                </a>
                <ul class="menu-sub">
                    @foreach ($setup->material_categories as $material_category)
                        <li class="menu-item @yield("result_by_material_category_id_{$material_category->id}-active")">
                            <a href="{{ route('result_by_material_category.index', $material_category->id) }}"
                                class="menu-link">
                                <div data-i18n="Without menu">
                                    {{ ucwords(str_replace('_', ' ', $material_category->name)) }}
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        <li class="menu-item @yield('report-active')">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-printer"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'report')) }}</div>
            </a>
            <ul class="menu-sub">
                @foreach ($setup->report_types as $report_type)
                        <li class="menu-item">
                            <a href="{{ route("report.index", $report_type->id) }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', $report_type->name)) }}
                                </div>
                            </a>
                        </li>
                @endforeach
            </ul>
        </li>

    </ul>

</aside>
<!-- / Menu -->
