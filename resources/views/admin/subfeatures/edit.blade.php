@component('admin.layouts.content', ['title'=>'ویرایش ویژگی اشتراک'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subfeatures.index') }}">ویژگی‌های اشتراک</a></li>
        <li class="breadcrumb-item active">ویرایش ویژگی اشتراک</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ویرایش ویژگی اشتراک</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.subfeatures.update', $subfeature->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">نام ویژگی</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="نام ویژگی را وارد کنید" value="{{ old('name', $subfeature->name) }}">
                            @error('name')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="key" class="col-sm-2 control-label">کلید ویژگی</label>
                            <input type="text" class="form-control" id="key" value="{{ $subfeature->key }}" readonly disabled>
                            <small class="form-text text-muted">کلید ویژگی قابل ویرایش نیست</small>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">توضیحات ویژگی</label>
                            <textarea name="description" class="form-control" id="description" placeholder="توضیحات ویژگی را وارد کنید">{{ old('description', $subfeature->description) }}</textarea>
                            @error('description')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">بروزرسانی ویژگی</button>
                        <a href="{{ route('admin.subfeatures.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endcomponent
