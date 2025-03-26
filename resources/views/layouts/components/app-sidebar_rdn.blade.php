<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-logo"
                    alt="logo">
                <img src="{{ asset('assets/images/brand/logo-1.png') }}" class="header-brand-img toggle-logo"
                    alt="logo">
                <img id="small-logo" src="{{ asset('assets/images/brand/small-logo.png') }}"
                    class="header-brand-img light-logo" alt="logo"
                    onerror="this.src=`{{ asset('assets/images/brand/small-logo.png') }}`">
                <img id="big-logo" src="{{ asset('assets/images/brand/big-logo.png') }}"
                    class="header-brand-img light-logo1" alt="logo" style="width:100px !important;"
                    onerror="this.src=`{{ asset('assets/images/brand/big-logo.png') }}`">
            </a><!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu">
                <li>
                    <h3>{{ trans('translation.menu.menu') }}</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{ url('/') }}">
                        <i class="side-menu__icon fa fa-home"></i>
                        <span class="side-menu__label">{{ trans('translation.menu.dashboard') }}</span>
                    </a>
                </li>
                @if (PermissionRole::checkPermission([Permission::PRODUCT, Permission::PURCHASE_ORDER]))
                    <li class="slide {{ Menu::isActiveMenu(['product', 'purchase-order', 'batch'], 'is-expanded') }}">
                        <a class="side-menu__item {{ Menu::isActiveMenu(['product', 'purchase-order', 'batch'], 'active') }}"
                            data-bs-toggle="slide" href="#">
                            <i class="side-menu__icon fa fa-archive"></i>
                            <span class="side-menu__label">{{ __('translation.menu.inventory') }}<i
                                    class="numberCircle">2</i></span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                            @if (PermissionRole::checkPermission([Permission::PRODUCT]))
                                <li><a href="{{ url('product') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['product'], 'active') }}">{{ __('translation.menu.product') }}</a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::PURCHASE_ORDER]))
                                <li><a href="{{ url('purchase-order') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['purchase-order'], 'active') }}">{{ __('translation.purchaseOrder.purchaseOrder') }}</a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::BATCH]))
                                <li><a href="{{ url('batch') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['batch'], 'active') }}">{{ __('translation.batch.batch') }}</a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::PURCHASE_ORDER]))
                                <li><a href="{{ url('receipt') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['receipt'], 'active') }}">{{ __('translation.receipt.management') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (PermissionRole::checkPermission([Permission::SALE_ORDER]))
                    <li class="slide">
                        <a class="side-menu__item {{ Menu::isActiveMenu(['sale-order', 'invoice'], 'active') }}"
                            data-bs-toggle="slide" href="#">
                            <i class="side-menu__icon fa fa-dollar"></i>
                            <span class="side-menu__label">{{ __('translation.menu.saleOrder') }} <i
                                    class="numberCircle">3</i></span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                            @if (PermissionRole::checkPermission([Permission::SALE_ORDER]))
                                <li><a href="{{ url('sale-order') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['sale-order'], 'active') }}"><?php echo __('translation.menu.saleOrder'); ?></a>
                                </li>
                                <li><a href="{{ url('invoice') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['invoice'], 'active') }}"><?php echo __('translation.invoice.invoice'); ?></a>
                                </li>
                            @endif
                        </ul>
                        </a>
                    </li>
                @endif
                @if (PermissionRole::checkPermission([Permission::SUPPLIER]))
                    <li class="slide">
                        <a class="side-menu__item {{ Menu::isActiveMenu(['supplier'], 'active') }}"
                            data-bs-toggle="slide" href="#">
                            <i class="side-menu__icon fa fa-industry"></i>
                            <span class="side-menu__label">{{ __('translation.menu.supplier') }} <i
                                    class="numberCircle">4</i></span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                            @if (PermissionRole::checkPermission([Permission::SUPPLIER]))
                                <li><a href="{{ url('supplier') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['supplier'], 'active') }}"><?php echo __('translation.menu.supplier'); ?></a>
                                </li>
                            @endif
                        </ul>
                        </a>
                    </li>
                @endif
                @if (PermissionRole::checkPermission([Permission::POSITION]))
                    <li class="slide">
                        <a class="side-menu__item {{ Menu::isActiveMenu(['position'], 'active') }}"
                            data-bs-toggle="slide" href="#">
                            <i class="side-menu__icon fa fa-users"></i>
                            <span class="side-menu__label">{{ __('translation.menu.hrm') }} <i
                                    class="numberCircle">6</i></span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                            @if (PermissionRole::checkPermission([Permission::POSITION]))
                                <li><a href="{{ url('position') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['position'], 'active') }}"><?php echo __('translation.menu.position'); ?></a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::DEPARTMENT]))
                                <li><a href="{{ url('department') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['department'], 'active') }}"><?php echo __('translation.menu.department'); ?></a>
                                </li>
                            @endif
                        </ul>
                        </a>
                    </li>
                @endif
                @if (PermissionRole::checkPermission([Permission::WAREHOUSE, Permission::SHELVE, Permission::CATEGORY]))
                    <li class="slide">
                        <a class="side-menu__item {{ Menu::isActiveMenu(['warehouse', 'shelve', 'category'], 'active') }}"
                            data-bs-toggle="slide" href="#">
                            <i class="side-menu__icon fa fa-cog"></i>
                            <span class="side-menu__label">{{ __('translation.menu.other') }} <i
                                    class="numberCircle">6</i></span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                            @if (PermissionRole::checkPermission([Permission::WAREHOUSE]))
                                <li><a href="{{ url('warehouse') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['warehouse'], 'active') }}"><?php echo __('translation.menu.warehouse'); ?></a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::SHELVE]))
                                <li><a href="{{ url('shelve') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['shelve'], 'active') }}"><?php echo __('translation.shelve.shelve'); ?></a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::CATEGORY]))
                                <li><a href="{{ url('category') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['category'], 'active') }}"><?php echo __('translation.category.category'); ?></a>
                                </li>
                            @endif
                        </ul>
                        </a>
                    </li>
                @endif
                @if (PermissionRole::checkPermission([Permission::CUSTOMER, Permission::UNIT, Permission::LOG]))
                    <li class="slide">
                        <a class="side-menu__item {{ Menu::isActiveMenu(['user', 'customer', 'unit', 'log'], 'active') }}"
                            data-bs-toggle="slide" href="#">
                            <i class="side-menu__icon fa fa-user"></i>
                            <span class="side-menu__label">{{ __('translation.menu.utility') }} <i
                                    class="numberCircle">8</i></span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu">
                            @if (PermissionUserRole::checkUserRole([UserRole::ADMIN]))
                                <li>
                                    <a href="{{ url('user') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['user'], 'active') }}">
                                        {{ __('translation.menu.user') }}
                                    </a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::CUSTOMER]))
                                <li><a href="{{ url('customer') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['customer'], 'active') }}"><?php echo __('translation.menu.customer'); ?></a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::UNIT]))
                                <li><a href="{{ url('unit') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['unit'], 'active') }}"><?php echo __('translation.menu.unit'); ?></a>
                                </li>
                            @endif
                            @if (PermissionRole::checkPermission([Permission::LOG]))
                                <li><a href="{{ url('log') }}"
                                        class="slide-item {{ Menu::isActiveMenu(['log'], 'active') }}"><?php echo __('translation.menu.log'); ?></a>
                                </li>
                            @endif
                        </ul>
                        </a>
                    </li>
                @endif
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
