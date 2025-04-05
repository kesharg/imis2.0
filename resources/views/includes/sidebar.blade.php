<!-- Last Modified Date: 07-05-2024
Developed By: Innovative Solution Pvt. Ltd. (ISPL)  (© ISPL, 2022) -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @if (request()->is('maps'))
        <a href="{{ url('/') }}" class="brand-link">
            <img src="" alt="">
            <span class="brand-text font-weight-light ">
                <img src="{{ asset('/img/logo-imis.png') }}" alt="Municipality Logo" id="sidebar-logo"
                    style="filter: brightness(0) invert(1) grayscale(1); float:left; line-height: .8;
            margin-right: 0.5rem; margin-top:3px; height:40px; width:80%">
            </span>
        </a>
    @else
        <a href="{{ url('/') }}" class="brand-link" id="sidebar-brand-link">
            <img src="{{ asset('/img/logo-imis.png') }}" alt="Municipality Logo" id="sidebar-logo"
                style="filter: brightness(0) invert(1) grayscale(1); float:left; line-height: .8;
        margin-right: 0.5rem; margin-top:3px; max-height:33px; width:70px">
            <img src="{{ asset('/img/logo-imis.png') }}" alt=" Municipality Logo" id="hello-text"
                style="filter: brightness(0) invert(1) grayscale(1); float:left; line-height : .8;
         margin-right: 0.5rem; margin-left:3%; max-height:60px; width:80%; display: none; ">
        </a>
    @endif
    <div class="sidebar" style='overflow-y: scroll;'>
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-house"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (auth()->user()->can('List Building Structures') || auth()->user()->can('List Building Surveys'))
                    <li class="nav-item {{ request()->is('building-info/*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('building-info/*') ? 'active' : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/buildingIMS.svg') }}" class="nav-icon"
                                alt="Building Icon">
                            <p>
                                Building IMS<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('List Building Structures') || auth()->user()->can('List Building Surveys'))
                                <li class="nav-item treeview menu-open">
                                    <a href="{{ action('BuildingInfo\BuildingDashboardController@index') }}"
                                        class="nav-link {{ request()->is('building-info/buildings/buildingdashboard') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Building Dashboard</p>
                                    </a>
                                </li>
                            @endif
                            @can('List Building Structures')
                                <li class="nav-item treeview menu-open">
                                    <a href="{{ action('BuildingInfo\BuildingController@index') }}"
                                        class="nav-link {{ request()->is('building-info/buildings') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Buildings</p>
                                    </a>
                                </li>
                            @endcan
                            @can('List Building Surveys')
                                <li class="nav-item">
                                    <a href="{{ action('BuildingInfo\BuildingSurveyController@index') }}"
                                        class="nav-link {{ request()->is('building-info/building-surveys') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Building Survey</p>
                                    </a>
                                </li>
                            @endcan
                            @can('List Low Income Communities')
                                <li class="nav-item">
                                    <a href="{{ action('LayerInfo\LowIncomeCommunityController@index') }}"
                                        class="nav-link {{ request()->is('layer-info/low-income-communities') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Low Income Community</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endif

                @if (Auth::user()->hasAnyPermission(
                        'List Containments',
                        'List Applications',
                        'List Emptyings',
                        'List Feedbacks',
                        'List Sludge Collections',
                        'List Service Providers',
                        'List Vacutug Types',
                        'List Employee Infos',
                        'List Treatment Plant Test',
                        'List Treatment Plants',
                        'List Help Desks',
                        'List Treatment Plant Test') || Auth::user()->hasRole('Super Admin'))
                    <li
                        class="nav-item {{ request()->is(
                            'fsm/fsmdashboard',
                            'fsm/containments',
                            'fsm/service-providers',
                            'fsm/employee-infos',
                            'fsm/desludging-vehicles',
                            'fsm/treatment-plants',
                            'fsm/treatment-plant-effectiveness',
                            'fsm/application',
                            'fsm/emptying',
                            'fsm/sludge-collection',
                            'fsm/feedback',
                            'fsm/help-desks',
                            'fsm/treatment-plant-test',
                        )
                            ? 'menu-is-opening menu-open'
                            : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is(
                                'fsm/fsmdashboard',
                                'fsm/containments',
                                'fsm/service-providers',
                                'fsm/employee-infos',
                                'fsm/desludging-vehicles',
                                'fsm/treatment-plants',
                                'fsm/treatment-plant-effectiveness',
                                'fsm/application',
                                'fsm/emptying',
                                'fsm/sludge-collection',
                                'fsm/feedback',
                                'fsm/help-desks',
                                'fsm/treatment-plant-test',
                            )
                                ? 'active'
                                : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/fecalSludgeIMS.svg') }}" class="nav-icon"
                                alt="Fecal Sludge Icon">

                            <p>
                                Fecal Sludge IMS<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item ">
                                <a href="{{ action('Fsm\FsmDashboardController@index') }}"
                                    class="nav-link {{ request()->is('fsm/fsmdashboard') ? 'active' : '' }}">
                                    <i class="nav-icon fa-solid fa-gauge"></i>
                                    <p>FSM Dashboard</p>
                                </a>
                            </li>
                           

                            @can('List Containments')
                                <li
                                    class="nav-item {{ request()->is('fsm/containments') ? 'menu-is-opening menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link {{ request()->is('fsm/containments') ? 'active subnav' : '' }}">
                                        <i class="nav-icon fa-regular fa-building"></i>
                                        <p>
                                            Containment IMS <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('List Containments')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\ContainmentController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/containments') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Containments</p>
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcan

                            @if (auth()->user()->can('List Service Providers') ||
                                    auth()->user()->can('List Employee Infos') ||
                                    auth()->user()->can('List Vacutug Types'))
                                <li
                                    class="nav-item {{ request()->is('fsm/service-providers', 'fsm/employee-infos', 'fsm/desludging-vehicles') ? 'menu-is-opening menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link {{ request()->is('fsm/service-providers', 'fsm/employee-infos', 'fsm/desludging-vehicles') ? 'active subnav' : '' }}">
                                        <i class="nav-icon fa-regular fa-building"></i>
                                        <p>
                                            Service Provider IMS <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('List Service Providers')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\ServiceProviderController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/service-providers') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Service Providers</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('List Employee Infos')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\EmployeeInfoController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/employee-infos') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Employee Information</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('List Vacutug Types')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\VacutugTypeController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/desludging-vehicles') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Desludging Vehicles</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif
                            @if (auth()->user()->can('List Treatment Plants') || auth()->user()->can('List Treatment Plant Test'))
                                <li
                                    class="nav-item {{ request()->is('fsm/treatment-plants', 'fsm/treatment-plant-test') ? 'menu-is-opening menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link  {{ request()->is('fsm/treatment-plants', 'fsm/treatment-plant-test') ? 'active subnav' : '' }}">
                                        <i class="nav-icon fa-regular fa-building"></i>
                                        <p>
                                            Treatment Plant IMS <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('List Treatment Plants')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\TreatmentPlantController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/treatment-plants') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Treatment Plants</p>
                                                </a>
                                            </li>
                                        @endcan
                                        {{-- @can('List Treatment Plant Test')
                                        <li class="nav-item">
                                            <a href="{{ action('Fsm\TreatmentPlantEffectivenessController@index') }}"
                                                class="nav-link {{ request()->is('fsm/treatment-plant-effectiveness') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Treatment Plant Effectiveness</p>
                                            </a>
                                        </li>
                                    @endcan --}}

                                        @can('List Treatment Plant Test')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\TreatmentPlantTestController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/treatment-plant-test') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p> Performance <br> Efficiency Test</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif
                            @if (Auth::user()->hasAnyPermission(
                                    'List Applications',
                                    'List Emptyings',
                                    'List Feedbacks',
                                    'List Sludge Collections',
                                    'List Help Desks') || Auth::user()->hasRole('Super Admin'))
                                <li
                                    class="nav-item  {{ request()->is('fsm/application', 'fsm/emptying', 'fsm/sludge-collection', 'fsm/feedback', 'fsm/help-desks') ? 'menu-is-opening menu-open' : '' }}">
                                    <a href="#"
                                        class="nav-link {{ request()->is('fsm/application', 'fsm/emptying', 'fsm/sludge-collection', 'fsm/feedback', 'fsm/help-desks') ? 'active subnav' : '' }}">
                                        <i class="nav-icon fa-regular fa-building"></i>
                                        <p>
                                            Emptying Service IMS <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('List Applications')
                                            <li class="nav-item">
                                                <a href="{{ route('application.index') }}"
                                                    class="nav-link {{ request()->is('fsm/application') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Application</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('List Emptyings')
                                            <li class="nav-item">
                                                <a href="{{ route('emptying.index') }}"
                                                    class="nav-link {{ request()->is('fsm/emptying') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Emptying</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('List Sludge Collections')
                                            <li class="nav-item">
                                                <a href="{{ route('sludge-collection.index') }}"
                                                    class="nav-link {{ request()->is('fsm/sludge-collection') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Sludge Collections</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('List Feedbacks')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\FeedbackController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/feedback') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Feedbacks</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('List Help Desks')
                                            <li class="nav-item">
                                                <a href="{{ action('Fsm\HelpDeskController@index') }}"
                                                    class="nav-link {{ request()->is('fsm/help-desks') ? 'active' : '' }}">
                                                    <i class="far fa-circle nav-icon"></i>
                                                    <p>Help Desks</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->hasAnyPermission(
                    'List Sanitation System Technology') || Auth::user()->hasRole('Super Admin'))
                <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fa-solid fa-recycle"></i>
                <p>
                    Sewer Connection ISS<i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                                    <a href="{{ action('SewerConnection\SewerConnectionController@index') }}"
                                    class="nav-link {{ request()->is('sewerconnection/sewerconnection') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sewer Connection</p>
                                    </a>
                                </li>
                            </ul>
        </li>
                
        @endif
                @if (auth()->user()->can('List CT/PT General Informations') || auth()->user()->can('List Male or Female User'))
                    {{-- // || auth()->user()->can('List Data Framework for JMP') --}}
                    <li
                        class="nav-item {{ request()->is('fsm/ctpt', 'fsm/ctpt-users') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('fsm/ctpt', 'fsm/ctpt-users') ? 'active' : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/ptctIMS.svg')}}" class="nav-icon" alt="PTCT  Icon">
                            <p>
                                PT/CT IMS<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">
                            @can('List CT/PT General Informations')
                                <li class="nav-item">

                                    <a href="{{ action('Fsm\CtptController@index') }}"
                                        class="nav-link {{ request()->is('fsm/ctpt') ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle nav-icon"></i>
                                        <p>Public / Community Toilets</p>
                                    </a>
                                </li>
                            @endcan
                            @can('List Male or Female User')
                                <li class="nav-item">
                                    <a href="{{ action('Fsm\CtptUserController@index') }}"
                                        class="nav-link {{ request()->is('fsm/ctpt-users') ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle nav-icon"></i>
                                        <p>PT Users Log</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                @endif

                @if (Auth::user()->hasAnyPermission('List KPI Target') || Auth::user()->hasRole('Super Admin'))
                    <li
                        class="nav-item {{ request()->is('cwis/*', 'fsm/kpi-dashboard', 'fsm/kpi-targets') ? 'menu-is-opening menu-open' : '' }}">
                        <a href=""
                            class="nav-link {{ request()->is('cwis/*', 'fsm/kpi-dashboard', 'fsm/kpi-targets') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-microscope"></i>
                            <p> CWIS IMS<i class="right fas fa-angle-left"></i> </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ url('cwis/cwis/getall') }}" id="cwis-link"
                                    class="nav-link {{ request()->is('cwis/cwis/getall') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p>CWIS Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('Cwis\CwisMneController@index') }}"
                                    class="nav-link {{ request()->is('cwis/cwis/cwis-df-mne') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p>CWIS Generator</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ action('Fsm\KpiDashboardController@index') }}"
                                    class="nav-link {{ request()->is('fsm/kpi-dashboard') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p>KPI Dashboard </p>
                                </a>
                            </li>
                            @can('List KPI Target')
                                <li class="nav-item">
                                    <a href="{{ action('Fsm\KpiTargetController@index') }}"
                                        class="nav-link {{ request()->is('fsm/kpi-targets') ? 'active' : '' }}">
                                        <i class="nav-icon far fa-circle nav-icon"></i>
                                        <p>KPI Target </p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endif


                {{--  --}}
                @if (auth()->user()->can('List Roadlines') ||
                        auth()->user()->can('List Drains') ||
                        auth()->user()->can('List Sewers') ||
                        auth()->user()->can('List WaterSupply Network'))
                    <li class="nav-item {{ request()->is('utilityinfo/*') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('utilityinfo/*') ? 'active' : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/utilityIMS.svg')}}" class="nav-icon" alt="Utility Icon">
                            <p>
                                Utility IMS<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ action('UtilityInfo\UtilityDashboardController@index') }}"
                                    class="nav-link {{ request()->is('utilityinfo/utilitydashboard') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Utility Dashboard</p>
                                </a>
                            </li>
                          
                            @can('List Roadlines')
                                <li class="nav-item">
                                    <a href="{{ action('UtilityInfo\RoadlineController@index') }}"
                                        class="nav-link {{ request()->is('utilityinfo/roadlines') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Road Network </p>
                                    </a>
                                </li>
                            @endcan
                            @can('List Sewers')
                                <li class="nav-item">
                                    <a href="{{ action('UtilityInfo\SewerLineController@index') }}"
                                        class="nav-link {{ request()->is('utilityinfo/sewerlines') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sewer Network </p>
                                    </a>
                                </li>
                            @endcan
                            @can('List WaterSupply Network')
                                <li class="nav-item">
                                    <a href="{{ action('UtilityInfo\WaterSupplysController@index') }}"
                                        class="nav-link {{ request()->is('utilityinfo/watersupplys') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Water Supply Network </p>
                                    </a>
                                </li>
                            @endcan
                            @can('List Drains')
                                <li class="nav-item">
                                    <a href="{{ action('UtilityInfo\DrainController@index') }}"
                                        class="nav-link {{ request()->is('utilityinfo/drains') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Drain Network </p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endif
                @if (auth()->user()->can('List Property Tax Collection'))
                    <li class="nav-item">
                        <a href="{{ route('tax-payment.index') }}"
                            class="nav-link {{ request()->is('tax-payment') ? 'active' : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/propertyTaxCollectionIMS.svg')}}" class="nav-icon"
                                >
                            <p>
                                Property Tax Collection ISS
                            </p>
                        </a>
                        {{--
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tax-payment.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-file-import"></i>
                                <p>File Import</p>
                            </a>
                        </li>
                    </ul>
                    --}}
                    </li>
                @endif

                @if (auth()->user()->can('List Water Supply Payment Info'))
                    <li class="nav-item">
                        <a href="{{ route('watersupply-payment.index') }}"
                            class="nav-link {{ request()->is('watersupply-payment') ? 'active' : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/watersupplyISS.svg')}}" class="nav-icon"
                                alt="Water Supply ISS Icon">
                            <p>
                                Water Supply ISS
                            </p>
                        </a>
                        {{--
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('watersupply-payment.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-file-import"></i>
                                <p>File Import</p>
                            </a>
                        </li>
                    </ul>
                    --}}
                    </li>
                @endif

                <li
                    class="nav-item  {{ request()->is('export-shp-kml', 'maps') ? 'menu-is-opening menu-open' : '' }}">
                    <a href="{{ route('watersupply-payment.index') }}"
                        class="nav-link  {{ request()->is('export-shp-kml', 'maps') ? 'active' : '' }}">
                        <img src="{{ asset('img/svg/imis-icons/urbanManagementDSS.svg')}}" class="nav-icon"
                            alt="Urban Management DSS">
                        <p>
                            Urban Management DSS <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('Data Export Map Tools')
                            <li class="nav-item">
                                <a href="{{ action('ExportShpKmlController@index') }}"
                                    class="nav-link {{ request()->is('export-shp-kml') ? 'active' : '' }}">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p>Export Data </p>
                                </a>
                            </li>
                        @endcan
                        {{-- @can() --}}
                        <li class="nav-item">
                            <a href="{{ action('MapsController@index') }}"
                                class="nav-link {{ request()->is('maps') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle nav-icon"></i>
                                <p>Map Feature </p>
                            </a>
                        </li>
                        {{-- @endcan --}}

                    </ul>
                </li>


                @if (auth()->user()->can('List Yearly Waterborne Cases') || auth()->user()->can('List Hotspot Identifications'))
                    <li
                        class="nav-item {{ request()->is('fsm/hotspots', 'publichealth/waterborne', 'publichealth/water-samples') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('fsm/hotspots', 'publichealth/waterborne' , 'publichealth/water-samples') ? 'active' : '' }}">
                            <img src="{{ asset('img/svg/imis-icons/publicHealthISS.svg')}}" class="nav-icon"
                                alt="Fecal Sludge Icon">
                            <p>
                                Public Health ISS <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- <li class="nav-item">
                            <a href="{{ action('PublicHealth\DengueCaseController@index') }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dengue Cases</p>
                            </a>
                        </li> --}}
                        @can('List Water Samples')
                            <li class="nav-item">
                                <a href="{{ action('PublicHealth\WaterSamplesController@index') }}" class="nav-link {{ request()->is('publichealth/water-samples') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                        <p>Water Samples</p>
                                </a>
                            </li>
                            @endcan
                            @can('List Yearly Waterborne Cases')
                                <li class="nav-item">
                                    <a href="{{ action('Fsm\HotspotController@index') }}"
                                        class="nav-link {{ request()->is('fsm/hotspots') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Waterborne Hotspot </p>
                                    </a>
                                </li>
                            @endcan
                            @can('List Hotspot Identifications')
                                <li class="nav-item">
                                    <a href="{{ action('PublicHealth\YearlyWaterborneController@index') }}"
                                        class="nav-link {{ request()->is('publichealth/waterborne') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Waterborne Cases Information </p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endif

                @if (auth()->user()->can('List Users') || auth()->user()->can('List Roles'))
                    <li
                        class="nav-item {{ request()->is('auth/*', 'fsm/treatment-plant-performance-test') ? 'menu-is-opening menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('auth/*') ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-gear"></i>
                            <p>
                                Settings<i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item {{ request()->is('auth/*') ? 'menu-is-opening menu-open' : '' }}">
                                <a href="{{ action('Fsm\TreatmentplantPerformanceTestController@index') }}"
                                    class="nav-link {{ request()->is('fsm/treatment-plant-performance-test') ? 'active subnav' : '' }}">
                                    <img class=" nav-icon"src="{{ asset('/img/icons/treatment-plant-light.png') }}">
                                    <p> Performance <br> Efficiency Standards</p>
                                </a>
                            </li>

                            <li class="nav-item {{ request()->is('auth/*') ? 'menu-is-opening menu-open' : '' }}">
                                <a href="{{ action('Fsm\CwisSettingController@index') }}"
                                    class="nav-link {{ request()->is('fsm/cwis-setting') ? 'active subnav' : '' }}">
                                    <i class="nav-icon fa-solid fa-microscope"></i>
                                    <p>CWIS Setting</p>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->is('auth/*') ? 'menu-is-opening menu-open' : '' }}"><a
                                    href="#"
                                    class="nav-link {{ request()->is('auth/*') ? 'active subnav' : '' }}">
                                    <i class="fa-solid fa-users"></i>
                                    <p>
                                        User Information Management<i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('List Users')
                                        <li class="nav-item">
                                            <a href="{{ action('Auth\UserController@index') }}"
                                                class="nav-link {{ request()->is('auth/users') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Users</p>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('List Roles')
                                        <li class="nav-item">
                                            <a href="{{ action('Auth\RoleController@index') }}"
                                                class="nav-link {{ request()->is('auth/roles') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Roles</p>
                                            </a>
                                        </li>
                                    @endcan

                                </ul>
                            </li>

                        </ul>
                    </li>
                @endif

                {{-- <li class="nav-item ">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-globe"></i>
                        <p>
                            Language<i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ action('LocalController@setLang', ['locale' => 'en']) }}" class="nav-link">
                                <p> English</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ action('LocalController@setLang', ['locale' => 'nep']) }}" class="nav-link">
                                <p> Nepali</p>
                            </a>
                        </li>


                    </ul>
                </li> --}}


            </ul>
        </nav>

    </div>

</aside>
<script>
    function toggleElements() {

        var logo = document.getElementById('sidebar-logo');
        var helloText = document.getElementById('hello-text');

        if (logo.style.display === 'none') {
            logo.style.display = 'inline';
            helloText.style.display = 'none';
        } else {
            logo.style.display = 'none';
            helloText.style.display = 'inline';
        }
    }
</script>
