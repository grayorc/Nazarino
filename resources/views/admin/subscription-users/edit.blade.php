@component('admin.layouts.content', ['title'=>'ویرایش اشتراک کاربر'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.subscription-users.index') }}">اشتراک کاربران</a></li>
        <li class="breadcrumb-item active">ویرایش اشتراک کاربر</li>
    @endslot

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">فرم ویرایش اشتراک کاربر</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form class="form-horizontal" action="{{ route('admin.subscription-users.update', $subscriptionUser->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="form-group col-md-6">
                            <label for="user_id" class="col-sm-2 control-label">کاربر</label>
                            <select name="user_id" class="form-control" id="user_id">
                                <option value="">انتخاب کاربر</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $subscriptionUser->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name ?? $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="subscription_tier_id" class="col-sm-2 control-label">سطح اشتراک</label>
                            <select name="subscription_tier_id" class="form-control" id="subscription_tier_id">
                                <option value="">انتخاب سطح اشتراک</option>
                                @foreach($subscriptionTiers as $tier)
                                    <option value="{{ $tier->id }}" {{ old('subscription_tier_id', $subscriptionUser->subscription_tier_id) == $tier->id ? 'selected' : '' }}>
                                        {{ $tier->title }} ({{ number_format($tier->price) }} تومان)
                                    </option>
                                @endforeach
                            </select>
                            @error('subscription_tier_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="status" class="col-sm-2 control-label">وضعیت</label>
                            <select name="status" class="form-control" id="status">
                                <option value="active" {{ old('status', $subscriptionUser->status) == 'active' ? 'selected' : '' }}>فعال</option>
                                <option value="inactive" {{ old('status', $subscriptionUser->status) == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                                <option value="pending" {{ old('status', $subscriptionUser->status) == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="cancelled" {{ old('status', $subscriptionUser->status) == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                            </select>
                            @error('status')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="starts_at" class="col-sm-2 control-label">تاریخ شروع</label>
                            <input type="date" name="starts_at" class="form-control" id="starts_at" value="{{ old('starts_at', $subscriptionUser->starts_at->format('Y-m-d')) }}">
                            @error('starts_at')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ends_at" class="col-sm-2 control-label">تاریخ پایان</label>
                            <input type="date" name="ends_at" class="form-control" id="ends_at" value="{{ old('ends_at', $subscriptionUser->ends_at->format('Y-m-d')) }}">
                            @error('ends_at')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">بروزرسانی اشتراک</button>
                        <a href="{{ route('admin.subscription-users.index') }}" class="btn btn-default float-left">لغو</a>
                    </div>
                    <!-- /.card-footer -->
                </form>
            </div>
        </div>
    </div>
@endcomponent
