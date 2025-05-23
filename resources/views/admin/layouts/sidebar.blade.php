<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin" class="brand-link" style="background-color: #17a2b8 !important;">
      <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8;background-color: white !important;">
      <span class="brand-text font-weight-light">پنل مدیریت</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="direction: ltr">
      <div style="direction: rtl">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image square-wrapper">
            <img src="/dist/img/user8-128x128.jpg" class="img-circle elevation-2 img-fluid" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"> علی</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->

            @can('view-user')
            <!-- کاربران -->
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <!-- <i class="fa fa-user nav-icon"></i> -->
                <i class="ri-group-fill nav-icon"></i>
                <p>
                  کاربران
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="
                  {{ route('admin.users.index') }}
                   " class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>لیست کاربران</p>
                  </a>
                </li>
                @can('create-user')
                    <li class="nav-item">
                      <a href="
                      {{ route('admin.users.create') }}
                       " class="nav-link">
                        <i class="ri-circle-line nav-icon"></i>
                        <p>ساخت کاربر جدید</p>
                      </a>
                    </li>
                @endcan
              </ul>
            </li>
            @endcan
            
            <!-- نقش‌ها و دسترسی‌ها -->
            @if(auth()->user()->hasPermission('view-role') || auth()->user()->hasPermission('view-permission'))
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="ri-shield-star-fill nav-icon"></i>
                <p>
                  نقش‌ها و دسترسی‌ها
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view-role')
                <li class="nav-item">
                  <a href="{{ route('admin.roles.index') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>لیست نقش‌ها</p>
                  </a>
                </li>
                @endcan
                @can('create-role')
                <li class="nav-item">
                  <a href="{{ route('admin.roles.create') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>ایجاد نقش جدید</p>
                  </a>
                </li>
                @endcan
                @can('view-permission')
                <li class="nav-item">
                  <a href="{{ route('admin.permissions.index') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>لیست دسترسی‌ها</p>
                  </a>
                </li>
                @endcan

              </ul>
            </li>
            @endif
            
            <!-- اشتراک‌ها -->
            @if(auth()->user()->hasPermission('view-sub-feature') || auth()->user()->hasPermission('view-subscription') || auth()->user()->hasPermission('view-user-subscription'))
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="ri-vip-crown-fill nav-icon"></i>
                <p>
                  مدیریت اشتراک‌ها
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @can('view-sub-feature')
                <li class="nav-item">
                  <a href="{{ route('admin.subfeatures.index') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>ویژگی‌های اشتراک</p>
                  </a>
                </li>
                @endcan
                @can('view-subscription')
                <li class="nav-item">
                  <a href="{{ route('admin.subscription-tiers.index') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>سطوح اشتراک</p>
                  </a>
                </li>
                @endcan
                @can('view-user-subscription')
                <li class="nav-item">
                  <a href="{{ route('admin.subscription-users.index') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>اشتراک کاربران</p>
                  </a>
                </li>
                @endcan
              </ul>
            </li>
            @endif
            
            <!-- نظرسنجی‌ها -->
            @can('view-election')
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="ri-bar-chart-fill nav-icon"></i>
                <p>
                  نظرسنجی‌ها
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('admin.elections.index') }}" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>لیست نظرسنجی‌ها</p>
                  </a>
                </li>
              </ul>
            </li>
            @endcan
            <!--  -->
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    </div>
    <!-- /.sidebar -->
  </aside>
