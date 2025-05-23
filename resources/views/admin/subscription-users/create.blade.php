@component('admin.layouts.content', ['title'=>'ایجاد اشتراک کاربر جدید'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-users.index') }}">اشتراک کاربران</a></li>
        <li class="breadcrumb-item active">ایجاد اشتراک کاربر جدید</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ایجاد اشتراک کاربر</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.subscription-users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id" class="col-sm-2 control-label">کاربر</label>
                            <select name="user_id" class="form-control" id="user_id">
                                <option value="">انتخاب کاربر</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name ?? $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="subscription_tier_id" class="col-sm-2 control-label">سطح اشتراک</label>
                            <select name="subscription_tier_id" class="form-control" id="subscription_tier_id">
                                <option value="">انتخاب سطح اشتراک</option>
                                @foreach($subscriptionTiers as $tier)
                                    <option value="{{ $tier->id }}" {{ old('subscription_tier_id') == $tier->id ? 'selected' : '' }}>
                                        {{ $tier->title }} ({{ number_format($tier->price) }} تومان)
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_tier_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">وضعیت</label>
                            <select name="status" class="form-control" id="status">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>فعال</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label">مدت اشتراک</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" name="duration" class="form-control" placeholder="مدت" value="{{ old('duration', 1) }}" min="1">
                                    @error('duration')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <select name="duration_unit" class="form-control">
                                        <option value="days" {{ old('duration_unit') == 'days' ? 'selected' : '' }}>روز</option>
                                        <option value="months" {{ old('duration_unit') == 'months' ? 'selected' : '' }}>ماه</option>
                                        <option value="years" {{ old('duration_unit') == 'years' ? 'selected' : '' }}>سال</option>
                                    </select>
                                    @error('duration_unit')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">ثبت اشتراک</button>
                        <a href="{{ route('admin.subscription-users.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endcomponent
