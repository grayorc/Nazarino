@component('admin.layouts.content', ['title'=>'نظرسنجی ها'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">نظرسنجی ها</li>
        <li class="breadcrumb-item active">{{ $elections->total() }}</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">جدول نظرسنجی ها</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.elections.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('admin.elections.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
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
                            @can('view-election')
                                <a href="{{ route('admin.elections.export', request()->query()) }}" class="btn btn-success">
                                    <i class="ri-file-excel-2-line"></i> خروجی اکسل
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-header border-bottom-0">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.elections.index', []) }}"
                           class="btn {{ (request('filter') == null && request('status') == null) ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            همه
                        </a>

                        <a href="{{ route('admin.elections.index', array_merge(request()->except(['filter']), ['filter' => 'visible'])) }}"
                           class="btn {{ request('filter') == 'visible' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            عمومی
                        </a>

                        <a href="{{ route('admin.elections.index', array_merge(request()->except('filter'), ['filter' => 'hidden'])) }}"
                           class="btn {{ request('filter') == 'hidden' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            خصوصی
                        </a>

                        <a href="{{ route('admin.elections.index', array_merge(request()->except('status'), ['status' => 'open'])) }}"
                           class="btn {{ request('status') == 'open' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            باز
                        </a>

                        <a href="{{ route('admin.elections.index', array_merge(request()->except('status'), ['status' => 'closed'])) }}"
                           class="btn {{ request('status') == 'closed' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            بسته
                        </a>
                    </div>
                </div>

                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" lang="en">
                        @fragment('table-section')
                            <tbody id="table-section" style="font-family: 'Vazir', sans-serif !important;">
                            <tr>
                                <th>شماره</th>
                                <th>عنوان</th>
                                <th>توضیحات</th>
                                <th>وضعیت</th>
                                <th>تاریخ ایجاد</th>
                                <th>تاریخ پایان</th>
                                <th>عملیات</th>
                            </tr>
                            @foreach ($elections as $election)
                                <tr id="row-{{ $election->id }}">
                                    <td>{{ $election->id }}</td>
                                    <td>{{ $election->title }}</td>
                                    <td>{{ Str::limit($election->description, 50) }}</td>
                                    <td>
                                        @if($election->is_open)
                                            <span class="badge badge-success">باز</span>
                                        @else
                                            <span class="badge badge-danger">بسته</span>
                                        @endif
                                    </td>
                                    <td>{{ verta($election->created_at)->format('Y/m/d') }}</td>
                                    <td>
                                        @if($election->end_date == null)
                                            <span class="badge badge-warning">نامحدود</span>
                                        @elseif($election->end_date > now())
                                            {{ verta($election->end_date)->format('Y/m/d') }}
                                        @else
                                            <span class="badge badge-danger">پایان یافته</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('delete-election', $election)
                                        <button class="btn btn-light btn-sm text-danger"
                                                hx-delete="{{ route('admin.elections.destroy', $election->id) }}"
                                                hx-trigger="click"
                                                hx-target="#row-{{ $election->id }}"
                                                hx-swap="outerHTML"
                                                hx-confirm="آیا مطمئن هستید که می‌خواهید این نظرسنجی را حذف کنید؟"
                                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                        ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                        @endcan
                                        @can('edit-election', $election)
                                        <a href="{{ route('admin.elections.edit', $election->id) }}">
                                            <button class="btn btn-light btn-sm text-primary">
                                                <i class="ri-edit-2-line ri-1x"></i>
                                            </button>
                                        </a>
                                        @endcan
                                        <a href="{{ route('election.show', $election->id) }}">
                                            <button class="btn btn-light btn-sm text-info">
                                                <i class="ri-eye-line ri-1x"></i>
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
                            صفحه <span class="font-weight-bold text-dark">{{ $elections->currentPage() }} از {{ $elections->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $elections->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $elections->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $elections->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $elections->onLastPage() ? 'disabled opacity-50' : '' }}">
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
