@component('admin.layouts.content', ['title'=>'ایجاد سطح اشتراک جدید'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-tiers.index') }}">سطوح اشتراک</a></li>
        <li class="breadcrumb-item active">ایجاد سطح اشتراک جدید</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ایجاد سطح اشتراک</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.subscription-tiers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">عنوان سطح اشتراک</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="عنوان سطح اشتراک را وارد کنید" value="{{ old('title') }}">
                            @error('title')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-sm-2 control-label">قیمت (تومان)</label>
                            <input type="number" name="price" class="form-control" id="price" placeholder="قیمت را وارد کنید" value="{{ old('price') }}">
                            @error('price')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">ویژگی‌های اشتراک</label>
                            <div class="row">
                                @foreach($subFeatures as $subFeature)
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="sub_features[]" value="{{ $subFeature->id }}" id="subfeature_{{ $subFeature->id }}">
                                            <label class="custom-control-label" for="subfeature_{{ $subFeature->id }}">{{ $subFeature->name }}</label>
                                            <small class="d-block text-muted">{{ $subFeature->description }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('sub_features')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ثبت سطح اشتراک</button>
                        <a href="{{ route('admin.subscription-tiers.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endcomponent
