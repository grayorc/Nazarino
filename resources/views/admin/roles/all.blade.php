@component('admin.layouts.content', ['title'=>'نقش‌ها'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">نقش‌ها</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لیست نقش‌ها</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.roles.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('admin.roles.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
                                <i class="ri-reset-left-line"></i>
                            </button>
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو"
                                       autocomplete="off"
                                >

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="ri-search-line ri-lg"></i></button>
                                </div>
                            </div>
                        </form>
                        <div class="btn-group-sm mr-1">
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-info">ایجاد نقش جدید</a>
                            @can('view-role')
                                <a href="{{ route('admin.roles.export', request()->query()) }}" class="btn btn-success mr-1">
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
                                <th>شناسه</th>
                                <th>نام</th>
                                <th>نام نمایشی</th>
                                <th>توضیحات</th>
                                <th>عملیات</th>
                            </tr>
                            @foreach ($roles as $role)
                                <tr id="row-{{ $role->id }}">
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->display_name }}</td>
                                    <td>{{ $role->description }}</td>
                                    <td>
                                        <button class="btn btn-light btn-sm text-danger"
                                                hx-delete="{{ route('admin.roles.destroy', $role->id) }}"
                                                hx-trigger="click"
                                                hx-target="#row-{{ $role->id }}"
                                                hx-swap="outerHTML"
                                                hx-confirm="آیا مطمئن هستید که می‌خواهید این نقش را حذف کنید؟"
                                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                        ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                        <a href="{{ route('admin.roles.edit', $role) }}">
                                            <button class="btn btn-light btn-sm text-primary">
                                                <i class="ri-edit-2-line ri-1x"></i>
                                            </button>
                                        </a>
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
                            صفحه <span class="font-weight-bold text-dark">{{ $roles->currentPage() }} از {{ $roles->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $roles->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $roles->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $roles->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $roles->onLastPage() ? 'disabled opacity-50' : '' }}">
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
@endcomponent
