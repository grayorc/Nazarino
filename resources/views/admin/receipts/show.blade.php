@component('admin.layouts.content', ['title'=>'جزئیات تراکنش'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.receipts.index') }}">تراکنش‌ها</a></li>
        <li class="breadcrumb-item active">جزئیات تراکنش</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">جزئیات تراکنش #{{ $receipt->id }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">اطلاعات تراکنش</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 200px;">شناسه تراکنش</th>
                                                    <td>{{ $receipt->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>شماره رسید</th>
                                                    <td>{{ $receipt->receipt_number }}</td>
                                                </tr>
                                                <tr>
                                                    <th>مبلغ</th>
                                                    <td>{{ number_format($receipt->amount) }} {{ $receipt->currency }}</td>
                                                </tr>
                                                <tr>
                                                    <th>وضعیت</th>
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
                                                </tr>
                                                <tr>
                                                    <th>روش پرداخت</th>
                                                    <td>{{ $receipt->payment_method }}</td>
                                                </tr>
                                                <tr>
                                                    <th>تاریخ پرداخت</th>
                                                    <td>{{ $receipt->paid_at ? verta($receipt->paid_at)->format('Y/m/d H:i') : 'نامشخص' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>تاریخ ایجاد</th>
                                                    <td>{{ verta($receipt->created_at)->format('Y/m/d H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">اطلاعات کاربر</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($receipt->user)
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th style="width: 200px;">شناسه کاربر</th>
                                                        <td>{{ $receipt->user->id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <td>{{ $receipt->user->username }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>نام</th>
                                                        <td>{{ $receipt->user->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>ایمیل</th>
                                                        <td>{{ $receipt->user->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>تلفن</th>
                                                        <td>{{ $receipt->user->phone_number ?? 'نامشخص' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>عملیات</th>
                                                        <td>
                                                            <a href="{{ route('admin.users.edit', $receipt->user->id) }}" class="btn btn-sm btn-primary">
                                                                <i class="ri-user-settings-line ml-1"></i>
                                                                ویرایش کاربر
                                                            </a>
                                                            <a href="{{ route('admin.subscription-users.edit', $receipt->subscriptionUser->id) }}" class="btn btn-sm btn-success">
                                                                <i class="ri-user-settings-line ml-1"></i>
                                                                ویرایش اشتراک کاربر
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            @else
                                                <div class="alert alert-warning">
                                                    اطلاعات کاربر در دسترس نیست.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($receipt->subscriptionUser)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0">اطلاعات اشتراک</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 200px;">شناسه اشتراک</th>
                                                    <td>{{ $receipt->subscriptionUser->id }}</td>
                                                </tr>
                                                <tr>
                                                    <th>نوع اشتراک</th>
                                                    <td>{{ $receipt->subscriptionUser->subscription->title ?? 'نامشخص' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>تاریخ شروع</th>
                                                    <td>{{ verta($receipt->subscriptionUser->start_date)->format('Y/m/d') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>تاریخ پایان</th>
                                                    <td>{{ verta($receipt->subscriptionUser->end_date)->format('Y/m/d') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>وضعیت</th>
                                                    <td>
                                                        @if($receipt->subscriptionUser->status == "active")
                                                            <span class="badge badge-success">فعال</span>
                                                        @else
                                                            <span class="badge badge-danger">غیرفعال</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-12 text-left">
                                    @can('remove-receipt')
                                    <form action="{{ route('admin.receipts.destroy', $receipt->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('آیا مطمئن هستید که می‌خواهید این تراکنش را حذف کنید؟')">
                                            <i class="ri-delete-bin-2-line ml-1"></i>
                                            حذف تراکنش
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endcomponent
