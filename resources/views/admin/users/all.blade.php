@component('admin.layouts.content', ['title'=>'کاربران'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">کاربران </li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لیست کاربران</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.users.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('admin.users.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
                                <i class="ri-reset-left-line"></i>
                            </button>
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو"

                                       autocomplete="off"
                                >

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="ri-user-search-line ri-lg"></i></button>
                                </div>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="admin" id="admin"
                                    > نمایش مدیران
                                </label>
                            </div>

                        </form>
                        <div class="btn-group-sm mr-1">
                            @can('view-user')
                                <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-success btn-sm">
                                    <i class="ri-file-excel-2-line"></i> خروجی اکسل
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" lang="en">
                        @fragment('table-section')
                            <tbody id="table-section" style="font-family: 'Vazir', sans-serif !important;">
                            <tr>
                                <th>شماره</th>
                                <th>نام</th>
                                <th>تاریخ عضویت</th>
                                <th>ایمیل</th>
                                <th>وضعیت ایمیل</th>
                                <th>عملیات</th>
                            </tr>
                            @foreach ($users as $user)
                                <tr id="row-{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ verta($user->created_at)->format('Y/m/d') }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if(is_null($user->email_verified_at))
                                            <span class="badge badge-danger">تایید نشده</span>
                                        @else
                                            <span class="badge badge-success">تایید شده</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('remove-user',$user)
                                        <button class="btn btn-light btn-sm text-danger"
                                                hx-delete="{{ route('admin.users.destroy', $user->id) }}"
                                                hx-trigger="click"
                                                hx-target="#row-{{ $user->id }}"
                                                hx-swap="outerHTML"
                                                hx-confirm="آیا مطمئن هستید که می‌خواهید این کاربر را حذف کنید؟"
                                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                        ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                        @endcan
                                        @can('edit-user', $user)
                                        <a href="{{ route('admin.users.edit',$user) }}">
                                            <button class="btn btn-light btn-sm text-primary">
                                                <i class="ri-edit-2-line ri-1x"></i>
                                            </button>
                                        </a>
                                        @endcan
                                        <a href="{{ route('admin.users.roles.edit',$user) }}">
                                            <button class="btn btn-light btn-sm text-info" title="مدیریت نقش‌ها و دسترسی‌ها">
                                                <i class="ri-shield-user-line ri-1x"></i>
                                            </button>
                                        </a>
                                        @can('create-user-subscription')
                                        <a href="{{ route('admin.subscription-users.create', ['user_id' => $user->id]) }}">
                                            <button class="btn btn-light btn-sm text-success" title="افزودن اشتراک">
                                                <i class="ri-vip-crown-line ri-1x"></i>
                                            </button>
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        @endfragment
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            صفحه <span class="font-weight-bold text-dark">{{ $users->currentPage() }} از {{ $users->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $users->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $users->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $users->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $users->onLastPage() ? 'disabled opacity-50' : '' }}">
                                <span>صفحه بعد</span>
                                <i class="ri-arrow-left-s-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
    <script>
        document.getElementById("admin").checked = false;
    </script>
@endcomponent
