@component('admin.layouts.content', ['title'=>'ویرایش دسترسی'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">دسترسی‌ها</a></li>
        <li class="breadcrumb-item active">ویرایش دسترسی</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ویرایش دسترسی</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">نام دسترسی</label>
                            <input type="text" class="form-control" id="name" value="{{ $permission->name }}" readonly disabled>
                            <small class="form-text text-muted">نام دسترسی قابل ویرایش نیست</small>
                        </div>
                        <div class="form-group">
                            <label for="display_name" class="col-sm-2 control-label">نام نمایشی دسترسی</label>
                            <input type="text" name="display_name" class="form-control" id="display_name" placeholder="نام نمایشی دسترسی را وارد کنید" value="{{ old('display_name', $permission->display_name) }}">
                            @error('display_name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">توضیحات دسترسی</label>
                            <textarea name="description" class="form-control" id="description" placeholder="توضیحات دسترسی را وارد کنید">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">بروزرسانی دسترسی</button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endcomponent
