@component('admin.layouts.content', ['title'=>'داشبورد'])
    @slot('breadcrumb')
        <li class="breadcrumb-item active">داشبورد </li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['elections_count'] }}</h3>
                            <p>نظرسنجی‌ها</p>
                        </div>
                        <div class="icon">
                            <i class="ri-bar-chart-box-line"></i>
                        </div>
                        <a href="{{ route('admin.elections.index') }}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['votes_count'] }}</h3>
                            <p>آرای ثبت شده</p>
                        </div>
                        <div class="icon">
                            <i class="ri-line-chart-line"></i>
                        </div>
                        <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['users_count'] }}</h3>
                            <p>کاربران ثبت شده</p>
                        </div>
                        <div class="icon">
                            <i class="ri-user-add-line"></i>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['active_subscriptions_count'] }}</h3>
                            <p>اشتراک‌های فعال</p>
                        </div>
                        <div class="icon">
                            <i class="ri-vip-crown-line"></i>
                        </div>
                        <a href="{{ route('admin.subscription-users.index') }}" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">
                                <i class="fa fa-pie-chart mr-1"></i>
                                نظرسنجی های اخیر
                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>عنوان</th>
                                        <th>وضعیت</th>
                                        <th style="width: 40px">تعداد آرا</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($elections as $key => $election)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $election['title'] }}</td>
                                            <td>
                                                <span class="badge bg-{{ $election['status'] }}">{{ $election['status_text'] }}</span>
                                            </td>
                                            <td><span class="badge bg-primary">{{ $election['vote_count'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- DIRECT CHAT -->
                    <div class="card direct-chat direct-chat-primary">
                        <div class="card-header">
                            <h3 class="card-title">اشتراک های فعال</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>عنوان</th>
                                    <th>قیمت</th>
                                    <th>تعداد ویژگی ها</th>
                                    <th style="width: 40px">کاربران فعال</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subscriptionTiers as $tier)
                                    <tr>
                                        <td>{{ $loop->index  + 1   }}</td>
                                        <td>{{ $tier->title }}</td>
                                        <td>{{ number_format($tier->price) }} تومان</td>
                                        <td>{{ $tier->features ? $tier->features->count() : 0 }}</td>
                                        <td><span class="badge bg-success">{{ $tier->subscription_users_count }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--/.direct-chat -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">

                    <!-- Election Types card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fa fa-chart-pie mr-1"></i>
                                نوع نظرسنجی ها
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>نوع نظرسنجی</th>
                                    <th style="width: 40px">تعداد</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($electionTypes as $type)
                                    <tr>
                                        <td>{{ $type['label'] }}</td>
                                        <td><span class="badge bg-primary">{{ $type['count'] }}</span></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- solid sales graph -->
                    <div class="card bg-info-gradient">
                        <div class="card-header no-border">
                            <h3 class="card-title">
                                <i class="fa fa-th mr-1"></i>
                                آمار آرا
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn bg-info btn-sm" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <div class="description-block mb-4">
                                        <h5 class="description-header">{{ $stats['votes_count'] }}</h5>
                                        <span class="description-text">کل آرا</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>تعداد آرا</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($votesData as $data)
                                            <tr>
                                                <td>{{ $data['date'] }}</td>
                                                <td>{{ $data['count'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                    <!--/.card -->
                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endcomponent

@section('scripts')

@endsection
