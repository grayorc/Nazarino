@component('admin.layouts.content', ['title'=>'تراکنش‌ها'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">تراکنش‌ها</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لیست تراکنش‌ها</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.receipts.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" style="height: 30px;" name="refresh" id="refresh" hx-get="{{ route('admin.receipts.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
                                <i class="ri-reset-left-line"></i>
                            </button>
                            <div class="input-group input-group-sm" style="width: 150px; height: 30px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو"
                                       autocomplete="off"
                                >

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="ri-search-line ri-lg"></i></button>
                                </div>
                            </div>
                            <div class="form-group mr-1">
                                <select name="status" class="form-control form-control-sm">
                                    <option value="">همه وضعیت‌ها</option>
                                    <option value="paid" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>ناموفق</option>
                                </select>
                            </div>
                        </form>
                        <div class="btn-group-sm mr-1">
                            @can('view-receipt')
                                <a href="{{ route('admin.receipts.export', request()->query()) }}" class="btn btn-success btn-sm">
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
                                <th>شماره رسید</th>
                                <th>کاربر</th>
                                <th>مبلغ</th>
                                <th>وضعیت</th>
                                <th>روش پرداخت</th>
                                <th>تاریخ پرداخت</th>
                                <th>عملیات</th>
                            </tr>
                            @foreach ($receipts as $receipt)
                                <tr id="row-{{ $receipt->id }}">
                                    <td>{{ $receipt->id }}</td>
                                    <td>{{ $receipt->receipt_number }}</td>
                                    <td>{{ $receipt->user ? $receipt->user->name : 'نامشخص' }}</td>
                                    <td>{{ number_format($receipt->amount) }} {{ $receipt->currency }}</td>
                                    <td>
                                        @if($receipt->status == 'completed')
                                            <span class="badge badge-success">تکمیل شده</span>
                                        @elseif($receipt->status == 'pending')
                                            <span class="badge badge-warning">در انتظار</span>
                                        @elseif($receipt->status == 'failed')
                                            <span class="badge badge-danger">ناموفق</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $receipt->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $receipt->payment_method }}</td>
                                    <td>{{ $receipt->paid_at ? verta($receipt->paid_at)->format('Y/m/d H:i') : 'نامشخص' }}</td>
                                    <td>
                                        @can('remove-receipt')
                                        <button class="btn btn-light btn-sm text-danger"
                                                hx-delete="{{ route('admin.receipts.destroy', $receipt->id) }}"
                                                hx-trigger="click"
                                                hx-target="#row-{{ $receipt->id }}"
                                                hx-swap="outerHTML"
                                                hx-confirm="آیا مطمئن هستید که می‌خواهید این تراکنش را حذف کنید؟"
                                                hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                        ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                        @endcan
                                        <a href="{{ route('admin.receipts.show', $receipt->id) }}">
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
                            صفحه <span class="font-weight-bold text-dark">{{ $receipts->currentPage() }} از {{ $receipts->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $receipts->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $receipts->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $receipts->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $receipts->onLastPage() ? 'disabled opacity-50' : '' }}">
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
