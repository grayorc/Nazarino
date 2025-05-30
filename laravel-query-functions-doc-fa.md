# مستندات توابع کوئری لاراول

## مقدمه

لاراول یک Query Builder روان و گویا ارائه می‌دهد که انجام عملیات پایگاه داده را آسان می‌کند. این سند متداول‌ترین توابع کوئری در لاراول را پوشش می‌دهد، از جمله روش‌های بازیابی، فیلتر کردن، مرتب‌سازی و دستکاری مجموعه‌ها.

## متدهای پایه Query Builder

### بازیابی نتایج

#### `get()`

تمام رکوردها را از کوئری به عنوان یک مجموعه بازیابی می‌کند:

```php
// دریافت تمام کاربران
$users = DB::table('users')->get();

// با Eloquent
$users = User::all();
$users = User::where('active', 1)->get();
```

#### `first()`

فقط اولین رکورد مطابق با کوئری را بازیابی می‌کند:

```php
// دریافت اولین کاربر فعال
$user = DB::table('users')->where('active', 1)->first();

// با Eloquent
$user = User::where('active', 1)->first();
```

#### `find()`

یک رکورد را با کلید اصلی آن بازیابی می‌کند:

```php
// دریافت کاربر با شناسه 1
$user = User::find(1);

// دریافت چندین کاربر با شناسه‌ها
$users = User::find([1, 2, 3]);
```

#### `findOrFail()`

یک رکورد را با کلید اصلی آن بازیابی می‌کند یا ModelNotFoundException پرتاب می‌کند:

```php
try {
    $user = User::findOrFail(1);
} catch (ModelNotFoundException $e) {
    // رسیدگی به پیدا نشدن
}
```

#### `value()`

یک مقدار واحد از اولین رکورد را بازیابی می‌کند:

```php
// دریافت نام اولین کاربر
$name = DB::table('users')->where('id', 1)->value('name');

// با Eloquent
$name = User::where('id', 1)->value('name');
```

### تکه‌تکه کردن نتایج

#### `chunk()`

مجموعه‌های نتایج بزرگ را در تکه‌ها پردازش می‌کند تا حافظه را حفظ کند:

```php
// پردازش 100 کاربر در هر بار
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // پردازش هر کاربر
    }
});
```

#### `chunkById()`

مشابه chunk() است اما از ID برای تکه‌بندی قابل اعتمادتر با مجموعه‌های داده بزرگ استفاده می‌کند:

```php
User::chunkById(100, function ($users) {
    foreach ($users as $user) {
        // پردازش هر کاربر
    }
});
```

#### `lazy()`

یک LazyCollection را برمی‌گرداند تا مجموعه‌های داده بزرگ را با حداقل استفاده از حافظه پردازش کند:

```php
User::lazy()->each(function ($user) {
    // پردازش هر کاربر با حداقل استفاده از حافظه
});
```

### توابع تجمعی

#### `count()`

تعداد رکوردها را می‌شمارد:

```php
$count = User::where('active', 1)->count();
```

#### `max()`, `min()`, `avg()`, `sum()`

توابع تجمعی را روی ستون‌ها انجام می‌دهد:

```php
$maxAge = User::max('age');
$minAge = User::min('age');
$avgAge = User::where('active', 1)->avg('age');
$totalPoints = User::sum('points');
```

## فیلتر کردن و مرتب‌سازی

### عبارات Where پایه

#### `where()`

یک عبارت where پایه اضافه می‌کند:

```php
// where پایه
$users = User::where('status', 'active')->get();

// با عملگر
$users = User::where('age', '>=', 18)->get();

// شرایط متعدد
$users = User::where('status', 'active')
             ->where('age', '>=', 18)
             ->get();
```

#### `orWhere()`

یک عبارت "or where" اضافه می‌کند:

```php
$users = User::where('status', 'active')
             ->orWhere('status', 'pending')
             ->get();
```

#### `whereBetween()`, `whereNotBetween()`

رکوردهایی را فیلتر می‌کند که مقدار یک ستون بین دو مقدار است:

```php
$users = User::whereBetween('age', [18, 65])->get();
$users = User::whereNotBetween('age', [18, 65])->get();
```

#### `whereIn()`, `whereNotIn()`

رکوردهایی را فیلتر می‌کند که مقدار یک ستون در یک آرایه است:

```php
$users = User::whereIn('id', [1, 2, 3])->get();
$users = User::whereNotIn('id', [1, 2, 3])->get();
```

#### `whereNull()`, `whereNotNull()`

رکوردهایی را فیلتر می‌کند که یک ستون null است یا null نیست:

```php
$users = User::whereNull('deleted_at')->get();
$users = User::whereNotNull('email_verified_at')->get();
```

#### `whereDate()`, `whereMonth()`, `whereDay()`, `whereYear()`

رکوردها را بر اساس اجزای تاریخ فیلتر می‌کند:

```php
$users = User::whereDate('created_at', '2023-01-01')->get();
$users = User::whereMonth('created_at', '1')->get(); // ژانویه
$users = User::whereDay('created_at', '1')->get();
$users = User::whereYear('created_at', '2023')->get();
```

### عبارات Where پیشرفته

#### `whereHas()`, `whereDoesntHave()`

رکوردها را بر اساس وجود رابطه فیلتر می‌کند:

```php
// کاربرانی که حداقل یک پست دارند
$users = User::whereHas('posts')->get();

// کاربرانی که حداقل 3 پست دارند
$users = User::whereHas('posts', function ($query) {
    $query->where('active', 1);
}, '>=', 3)->get();

// کاربرانی که هیچ پستی ندارند
$users = User::whereDoesntHave('posts')->get();
```

#### `whereExists()`

رکوردها را با استفاده از یک زیرکوئری فیلتر می‌کند:

```php
$users = User::whereExists(function ($query) {
    $query->select(DB::raw(1))
          ->from('posts')
          ->whereColumn('posts.user_id', 'users.id');
})->get();
```

#### `when()`

به صورت شرطی یک محدودیت کوئری را اعمال می‌کند:

```php
$users = User::when($request->has('search'), function ($query) use ($request) {
    return $query->where('name', 'like', '%' . $request->search . '%');
})->get();
```

### مرتب‌سازی نتایج

#### `orderBy()`

نتایج را بر اساس یک ستون مرتب می‌کند:

```php
// ترتیب صعودی (پیش‌فرض)
$users = User::orderBy('name')->get();

// ترتیب نزولی
$users = User::orderBy('name', 'desc')->get();

// چندین ستون
$users = User::orderBy('name')
             ->orderBy('created_at', 'desc')
             ->get();
```

#### `latest()`, `oldest()`

بر اساس تایم‌استمپ created_at مرتب می‌کند:

```php
// جدیدترین ابتدا (created_at نزولی)
$users = User::latest()->get();

// قدیمی‌ترین ابتدا (created_at صعودی)
$users = User::oldest()->get();

// استفاده از یک ستون تایم‌استمپ متفاوت
$users = User::latest('updated_at')->get();
```

#### `inRandomOrder()`

نتایج را به صورت تصادفی مرتب می‌کند:

```php
$randomUser = User::inRandomOrder()->first();
```

## متدهای مجموعه (Collection)

پس از بازیابی داده‌ها با `get()`، لاراول یک شیء Collection با متدهای مفید زیادی برمی‌گرداند:

### استخراج داده

#### `pluck()`

لیستی از مقادیر یک ستون واحد را استخراج می‌کند:

```php
// دریافت تمام نام‌های کاربران
$names = User::pluck('name');

// دریافت نام‌ها با شناسه‌ها به عنوان کلیدها
$names = User::pluck('name', 'id');
```

#### `keyBy()`

مجموعه را با یک فیلد کلیددار می‌کند:

```php
$users = User::all()->keyBy('id');
// دسترسی: $users[1] برای کاربر با شناسه 1
```

### نگاشت و تبدیل

#### `map()`

هر آیتم در مجموعه را تبدیل می‌کند:

```php
$userNames = User::all()->map(function ($user) {
    return $user->first_name . ' ' . $user->last_name;
});
```

#### `mapWithKeys()`

یک مجموعه را نگاشت می‌کند و جفت‌های کلید-مقدار را برمی‌گرداند:

```php
$userEmails = User::all()->mapWithKeys(function ($user) {
    return [$user->id => $user->email];
});
```

#### `flatMap()`

نگاشت می‌کند و نتیجه را مسطح می‌کند:

```php
$allPhoneNumbers = User::all()->flatMap(function ($user) {
    return $user->phone_numbers;
});
```

### فیلتر کردن

#### `filter()`

آیتم‌ها را در مجموعه فیلتر می‌کند:

```php
$activeUsers = User::all()->filter(function ($user) {
    return $user->active == 1;
});
```

#### `reject()`

مخالف filter - آیتم‌هایی که از آزمون عبور می‌کنند را حذف می‌کند:

```php
$inactiveUsers = User::all()->reject(function ($user) {
    return $user->active == 1;
});
```

#### `where()` (متد مجموعه)

مجموعه را با کلید/مقدار فیلتر می‌کند:

```php
$activeUsers = User::all()->where('active', 1);
```

### تکرار

#### `each()`

روی هر آیتم در مجموعه تکرار می‌کند:

```php
User::all()->each(function ($user) {
    // پردازش هر کاربر
    echo $user->name;
});
```

#### `tap()`

مجموعه را به یک callback می‌دهد بدون اینکه بر مجموعه تأثیر بگذارد:

```php
$users = User::all()->tap(function ($collection) {
    Log::info('تعداد مجموعه: ' . $collection->count());
})->filter(function ($user) {
    return $user->active == 1;
});
```

### تجمیع

#### `count()`, `sum()`, `avg()`, `max()`, `min()`

محاسباتی را روی مجموعه انجام می‌دهد:

```php
$count = User::all()->count();
$totalAge = User::all()->sum('age');
$averageAge = User::all()->avg('age');
$maxAge = User::all()->max('age');
$minAge = User::all()->min('age');
```

#### `groupBy()`

آیتم‌های مجموعه را بر اساس یک کلید گروه‌بندی می‌کند:

```php
$usersByRole = User::all()->groupBy('role');
```

#### `reduce()`

مجموعه را به یک مقدار واحد کاهش می‌دهد:

```php
$totalAge = User::all()->reduce(function ($carry, $user) {
    return $carry + $user->age;
}, 0);
```

## صفحه‌بندی

### `paginate()`

نتایج را صفحه‌بندی می‌کند:

```php
// 15 کاربر در هر صفحه
$users = User::paginate(15);

// با محدودیت‌های اضافی
$users = User::where('active', 1)->paginate(15);
```

### `simplePaginate()`

صفحه‌بندی ساده‌تر، سریع‌تر (بدون شمارش کل):

```php
$users = User::simplePaginate(15);
```

## روابط

### بارگذاری مشتاقانه (Eager Loading)

#### `with()`

روابط را به صورت مشتاقانه بارگذاری می‌کند تا از مشکلات کوئری N+1 جلوگیری کند:

```php
// بارگذاری کاربران با پست‌های آنها
$users = User::with('posts')->get();

// بارگذاری چندین رابطه
$users = User::with(['posts', 'profile'])->get();

// روابط تو در تو
$users = User::with('posts.comments')->get();
```

#### `withCount()`

مدل‌های مرتبط را می‌شمارد:

```php
// افزودن ویژگی posts_count
$users = User::withCount('posts')->get();
foreach ($users as $user) {
    echo $user->posts_count;
}

// با محدودیت‌ها
$users = User::withCount(['posts' => function ($query) {
    $query->where('active', 1);
}])->get();
```

## بهترین شیوه‌ها

1. **استفاده از مدل‌های Eloquent** در صورت امکان برای کد تمیزتر و قابل نگهداری‌تر
2. **بارگذاری مشتاقانه روابط** برای جلوگیری از مشکلات کوئری N+1
3. **استفاده از Chunking** برای پردازش مجموعه‌های داده بزرگ
4. **استفاده از Query Scopes** برای کپسوله کردن محدودیت‌های کوئری متداول
5. **استفاده از When()** برای بخش‌های شرطی کوئری
6. **استفاده از تراکنش‌های پایگاه داده** برای عملیاتی که نیاز به اتمیک بودن دارند

## مثال: ترکیب چندین متد

```php
// کوئری پیچیده ترکیب چندین متد
$users = User::with(['posts', 'profile'])
    ->withCount(['posts' => function ($query) {
        $query->where('active', 1);
    }])
    ->where('active', 1)
    ->when($request->has('search'), function ($query) use ($request) {
        return $query->where('name', 'like', '%' . $request->search . '%');
    })
    ->orderBy('name')
    ->paginate(15);

// پردازش با متدهای مجموعه
$formattedUsers = $users->map(function ($user) {
    return [
        'id' => $user->id,
        'full_name' => $user->first_name . ' ' . $user->last_name,
        'email' => $user->email,
        'active_posts_count' => $user->posts_count,
        'has_profile' => $user->profile ? true : false
    ];
});
```

## منابع

- [مستندات لاراول - Eloquent](https://laravel.com/docs/eloquent)
- [مستندات لاراول - Query Builder](https://laravel.com/docs/queries)
- [مستندات لاراول - Collections](https://laravel.com/docs/collections)
