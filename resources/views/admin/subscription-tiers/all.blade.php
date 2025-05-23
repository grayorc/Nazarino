@component('admin.layouts.content', ['title'=>'سطوح اشتراک'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">سطوح اشتراک</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لیست سطوح اشتراک</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.subscription-tiers.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('admin.subscription-tiers.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
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
                            <a href="{{ route('admin.subscription-tiers.create') }}" class="btn btn-info">ایجاد سطح اشتراک جدید</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" lang="en">
                        <tbody id="table-section" style="font-family: 'Vazir', sans-serif !important;">
                        <tr>
                            <th>شناسه</th>
                            <th>عنوان</th>
                            <th>قیمت</th>
                            <th>تعداد ویژگی‌ها</th>
                            <th>عملیات</th>
                        </tr>
                        @foreach ($subscriptionTiers as $tier)
                            <tr id="row-{{ $tier->id }}">
                                <td>{{ $tier->id }}</td>
                                <td>{{ $tier->title }}</td>
                                <td>{{ number_format($tier->price) }} تومان</td>
                                <td>{{ $tier->subFeatures->count() }}</td>
                                <td>
                                    <button class="btn btn-light btn-sm text-danger"
                                            hx-delete="{{ route('admin.subscription-tiers.destroy', $tier->id) }}"
                                            hx-trigger="click"
                                            hx-target="#row-{{ $tier->id }}"
                                            hx-swap="outerHTML"
                                            hx-confirm="آیا مطمئن هستید که می‌خواهید این سطح اشتراک را حذف کنید؟"
                                            hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                    ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                    <a href="{{ route('admin.subscription-tiers.edit', $tier) }}">
                                        <button class="btn btn-light btn-sm text-primary">
                                            <i class="ri-edit-2-line ri-1x"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            صفحه <span class="font-weight-bold text-dark">{{ $subscriptionTiers->currentPage() }} از {{ $subscriptionTiers->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $subscriptionTiers->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $subscriptionTiers->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $subscriptionTiers->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $subscriptionTiers->onLastPage() ? 'disabled opacity-50' : '' }}">
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
