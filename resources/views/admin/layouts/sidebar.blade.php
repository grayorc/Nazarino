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
            <!-- مدیران -->
            <!-- <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="ri-shield-star-fill nav-icon"></i>

                <p>
                  مدیران
                  <i class="right fa fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./index.html" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>تنظیمات دستری</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index2.html" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>تنظیمات وظایف</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index2.html" class="nav-link">
                    <i class="ri-circle-line nav-icon"></i>
                    <p>لیست مدیران</p>
                  </a>
                </li>
              </ul>
            </li> -->
            <!--  -->
        </nav>
        <!-- /.sidebar-menu -->
      </div>
    </div>
    <!-- /.sidebar -->
  </aside>
