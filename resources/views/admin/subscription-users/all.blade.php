@component('admin.layouts.content', ['title'=>'اشتراک کاربران'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">اشتراک کاربران</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لیست اشتراک کاربران</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.subscription-users.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('admin.subscription-users.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
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
                            <select name="status" class="form-control form-control-sm mr-1" style="width: 120px;">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="active">فعال</option>
                                <option value="inactive">غیرفعال</option>
                                <option value="pending">در انتظار</option>
                                <option value="cancelled">لغو شده</option>
                            </select>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" lang="en">
                        @fragment('table-section')
                        <tbody id="table-section" style="font-family: 'Vazir', sans-serif !important;">
                        <tr>
                            <th>شناسه</th>
                            <th>کاربر</th>
                            <th>سطح اشتراک</th>
                            <th>وضعیت</th>
                            <th>تاریخ شروع</th>
                            <th>تاریخ پایان</th>
                            <th>عملیات</th>
                        </tr>
                        @foreach ($subscriptionUsers as $subscription)
                            <tr id="row-{{ $subscription->id }}">
                                <td>{{ $subscription->id }}</td>
                                <td>{{ $subscription->user->name ?? $subscription->user->username }}</td>
                                <td>{{ $subscription->subscriptionTier->title }}</td>
                                <td>
                                    @if($subscription->status == 'active')
                                        <span class="badge badge-success">فعال</span>
                                    @elseif($subscription->status == 'inactive')
                                        <span class="badge badge-secondary">غیرفعال</span>
                                    @elseif($subscription->status == 'pending')
                                        <span class="badge badge-warning">در انتظار</span>
                                    @elseif($subscription->status == 'cancelled')
                                        <span class="badge badge-danger">لغو شده</span>
                                    @endif
                                </td>
                                <td>{{ verta($subscription->starts_at)->format('Y/m/d') }}</td>
                                <td>{{ verta($subscription->ends_at)->format('Y/m/d') }}</td>
                                <td>
                                    <button class="btn btn-light btn-sm text-danger"
                                            hx-delete="{{ route('admin.subscription-users.destroy', $subscription->id) }}"
                                            hx-trigger="click"
                                            hx-target="#row-{{ $subscription->id }}"
                                            hx-swap="outerHTML"
                                            hx-confirm="آیا مطمئن هستید که می‌خواهید این اشتراک را حذف کنید؟"
                                            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                    ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                    <a href="{{ route('admin.subscription-users.edit', $subscription) }}">
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
                            صفحه <span class="font-weight-bold text-dark">{{ $subscriptionUsers->currentPage() }} از {{ $subscriptionUsers->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $subscriptionUsers->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $subscriptionUsers->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $subscriptionUsers->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $subscriptionUsers->onLastPage() ? 'disabled opacity-50' : '' }}">
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
