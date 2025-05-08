@component('admin.layouts.content', ['title'=>'کاربران'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">ساخت کاربر</li>
    @endslot

    @include('admin.layouts.errors')
    <div class="card card-light"> 
        <div class="card-header">
          <h3 class="card-title">اطلاعات کاربر</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" method="post" action="{{ route('users.store') }}">
          @csrf
          <div class="card-body">
          <div class="form-group">
            <label for="InputٔName">نام کاربر</label>
            <input name="name" type="text" class="form-control" id="InputٔName" placeholder="ایمیل را وارد کنید">
          </div>
            <div class="form-group">
              <label for="InputEmail1">آدرس ایمیل</label>
              <input name="email" type="email" class="form-control" id="InputEmail1" placeholder="ایمیل را وارد کنید">
            </div>
            <div class="form-group">
              <label for="InputPassword1">گذرواژه</label>
              <input name="password" type="password" class="form-control" id="InputPassword1" placeholder="پسورد را وارد کنید">
            </div>
            <div class="form-group">
              <label for="InputPassword1">تایید گذرواژه</label>
              <input name="password_confirmation" type="password" class="form-control" id="InputPassword1" placeholder="پسورد را وارد کنید">
            </div>
            <div class="form-check">
              <input name="activateEmail" type="checkbox" class="form-check-input" id="Check1">
              <label class="form-check-label" for="Check1">فعالسازی ایمیل</label>
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">ارسال</button>
          </div>
        </form>
      </div>

@endcomponent