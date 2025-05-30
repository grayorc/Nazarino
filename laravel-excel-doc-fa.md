# مستندات Laravel Excel

## مقدمه

Laravel Excel یک پکیج قدرتمند است که صادر و وارد کردن فایل‌های Excel و CSV را در برنامه‌های Laravel آسان می‌کند. این پکیج بر پایه PhpSpreadsheet ساخته شده است و یک API ساده و زیبا برای کار با صفحات گسترده ارائه می‌دهد.

## نصب

```bash
composer require maatwebsite/excel
```

پس از نصب، فایل پیکربندی را منتشر کنید:

```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

## استفاده پایه

### ایجاد یک کلاس Export

برای ایجاد یک کلاس export، می‌توانید از دستور آرتیزان استفاده کنید:

```bash
php artisan make:export UsersExport --model=User
```

یا کلاس را به صورت دستی ایجاد کنید:

```php
<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}
```

### صادر کردن از یک کنترلر

```php
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

public function export() 
{
    return Excel::download(new UsersExport, 'users.xlsx');
}
```

## رابط‌های Export

Laravel Excel چندین رابط ارائه می‌دهد که می‌توانید برای سفارشی کردن صادرات خود پیاده‌سازی کنید:

### FromCollection

ساده‌ترین رابط که به شما امکان می‌دهد یک مجموعه از داده‌ها را صادر کنید:

```php
public function collection()
{
    return User::all();
}
```

### WithHeadings

اضافه کردن عناوین ستون به صادرات شما:

```php
public function headings(): array
{
    return [
        'شناسه',
        'نام',
        'ایمیل',
        'تاریخ ایجاد'
    ];
}
```

### WithMapping

نگاشت هر ردیف داده قبل از صادرات:

```php
public function map($user): array
{
    return [
        $user->id,
        $user->name,
        $user->email,
        verta($user->created_at)->format('Y/m/d'),
    ];
}
```

### ShouldAutoSize

تنظیم خودکار عرض ستون‌ها:

```php
class UsersExport implements FromCollection, ShouldAutoSize
{
    // ...
}
```

### WithStyles

اعمال استایل به سلول‌ها:

```php
public function styles(Worksheet $sheet)
{
    return [
        // استایل ردیف اول به صورت متن پررنگ
        1 => ['font' => ['bold' => true]],
    ];
}
```

### WithEvents

ثبت رویدادها برای سفارشی‌سازی بیشتر صادرات:

```php
public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            // سفارشی‌سازی صفحه پس از ایجاد آن
        },
    ];
}
```

## استایل‌دهی پیشرفته با PhpSpreadsheet

Laravel Excel از PhpSpreadsheet در زیر استفاده می‌کند و به شما دسترسی به تمام قابلیت‌های استایل‌دهی آن را می‌دهد:

### تنظیم مقادیر سلول

```php
$event->sheet->getDelegate()->setCellValue('A1', 'سلام دنیا');
```

### ادغام سلول‌ها

```php
$event->sheet->getDelegate()->mergeCells('A1:E1');
```

### تنظیم فونت

```php
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setName('Vazir');
```

### تنظیم تراز

```php
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
```

### تنظیم رنگ پس‌زمینه

```php
$event->sheet->getDelegate()->getStyle('A1:E1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('E2EFDA');
```

### تنظیم رنگ متن

```php
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setColor(
    new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN)
);
```

### تنظیم جهت RTL (برای متن فارسی/عربی)

```php
$event->sheet->getDelegate()->setRightToLeft(true);
```

## نمونه‌های عملی از پروژه ما

### 1. ساختار پایه کلاس Export

```php
class UserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $users;

    public function __construct($users = null)
    {
        $this->users = $users ?: User::with(['roles', 'subscriptions'])->get();
    }

    public function collection()
    {
        return $this->users;
    }
    
    // سایر متدها...
}
```

### 2. متد Export کنترلر

```php
public function export(Request $request)
{
    // ساخت کوئری با فیلترها
    $query = User::query()->with(['roles', 'subscriptions']);
    
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
    }
    
    $users = $query->get();
    
    return Excel::download(new UserExport($users), 'users-' . now()->format('Y-m-d') . '.xlsx');
}
```

### 3. استایل‌دهی متن فارسی

```php
public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            // تنظیم جهت RTL برای کل صفحه
            $event->sheet->getDelegate()->setRightToLeft(true);
            
            // اعمال تراز راست به تمام سلول‌ها
            $lastColumn = $event->sheet->getDelegate()->getHighestColumn();
            $lastRow = $event->sheet->getDelegate()->getHighestRow();
            $cellRange = 'A1:' . $lastColumn . $lastRow;
            
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
            // تنظیم فونت برای متن فارسی
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Vazir');
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
        }
    ];
}
```

### 4. اضافه کردن یک ردیف عنوان

```php
// اضافه کردن عنوان در بالا
$event->sheet->getDelegate()->insertNewRowBefore(1, 1);
$event->sheet->getDelegate()->mergeCells('A1:F1');
$event->sheet->getDelegate()->setCellValue('A1', 'گزارش کاربران سیستم');
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
$event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
```

### 5. قالب‌بندی شرطی

```php
// اعمال قالب‌بندی شرطی برای وضعیت
$lastDataRow = $event->sheet->getDelegate()->getHighestRow();
for ($row = 3; $row <= $lastDataRow; $row++) {
    // ستون وضعیت (D)
    $statusCell = 'D' . $row;
    $status = $event->sheet->getDelegate()->getCell($statusCell)->getValue();
    
    if ($status === 'فعال') {
        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C6EFCE'); // سبز برای فعال
    } else {
        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFC7CE'); // قرمز برای غیرفعال
    }
}
```

## انواع فایل‌های صادراتی

Laravel Excel از انواع مختلف فایل پشتیبانی می‌کند:

```php
// Excel XLSX
return Excel::download(new UsersExport, 'users.xlsx');

// Excel XLS
return Excel::download(new UsersExport, 'users.xls');

// CSV
return Excel::download(new UsersExport, 'users.csv');

// PDF (نیاز به تنظیمات اضافی دارد)
return Excel::download(new UsersExport, 'users.pdf');
```

## مشکلات رایج و راه‌حل‌ها

### 1. مشکلات حافظه با صادرات بزرگ

برای صادرات بزرگ، از صف‌ها استفاده کنید:

```php
return Excel::queue(new UsersExport, 'users.xlsx');
```

### 2. رندر متن فارسی/عربی

- استفاده از جهت RTL: `$sheet->setRightToLeft(true)`
- استفاده از فونت‌های سازگار مانند وزیر: `$sheet->getStyle()->getFont()->setName('Vazir')`
- اطمینان از نصب فونت روی سرور

### 3. قالب‌بندی تاریخ

استفاده از پکیج Verta برای تاریخ‌های فارسی:

```php
verta($user->created_at)->format('Y/m/d')
```

## منابع

- [مستندات رسمی Laravel Excel](https://docs.laravel-excel.com/3.1/getting-started/)
- [مستندات PhpSpreadsheet](https://phpspreadsheet.readthedocs.io/)
- [فونت وزیر (برای متن فارسی)](https://github.com/rastikerdar/vazir-font)
