@component('admin.layouts.content', ['title'=>'ایجاد ویژگی اشتراک جدید'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subfeatures.index') }}">ویژگی‌های اشتراک</a></li>
        <li class="breadcrumb-item active">ایجاد ویژگی اشتراک جدید</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ایجاد ویژگی اشتراک</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.subfeatures.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">نام ویژگی</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="نام ویژگی را وارد کنید" value="{{ old('name') }}">
                            @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="key" class="col-sm-2 control-label">کلید ویژگی</label>
                            <input type="text" name="key" class="form-control" id="key" placeholder="کلید ویژگی را وارد کنید (به انگلیسی و بدون فاصله)" value="{{ old('key') }}">
                            <small class="form-text text-muted">کلید باید منحصر به فرد و به صورت انگلیسی باشد (مثال: max_polls)</small>
                            @error('key')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">توضیحات ویژگی</label>
                            <textarea name="description" class="form-control" id="description" placeholder="توضیحات ویژگی را وارد کنید">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ثبت ویژگی</button>
                        <a href="{{ route('admin.subfeatures.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endcomponent
