@component('admin.layouts.content', ['title' => 'ویرایش کاربر'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">لیست کاربران</a></li>
        <li class="breadcrumb-item active">ویرایش کاربر</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ویرایش کاربر</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="username" class="col-sm-2 control-label">نام کاربری</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="نام کاربری را وارد کنید" value="{{ old('username', $user->username) }}">
                        </div>
                        <div class="form-group">
                            <label for="first_name" class="col-sm-2 control-label">نام</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="نام را وارد کنید" value="{{ old('first_name', $user->first_name) }}">
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-sm-2 control-label">نام خانوادگی</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="نام خانوادگی را وارد کنید" value="{{ old('last_name', $user->last_name) }}">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">ایمیل</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="ایمیل را وارد کنید" value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class="col-sm-2 control-label">شماره تماس</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="شماره تماس را وارد کنید" value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">پسورد جدید</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="پسورد را وارد کنید">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-sm-2 control-label">تکرار پسورد جدید</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="پسورد را تکرار کنید">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="activateEmail" id="activateEmail" {{ $user->email_verified_at ? 'checked' : '' }}>
                            <label class="form-check-label" for="activateEmail">اکانت فعال باشد</label>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ویرایش کاربر</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>

@endcomponent
