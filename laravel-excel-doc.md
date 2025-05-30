# Laravel Excel Documentation

## Introduction

Laravel Excel is a powerful package that makes it easy to export and import Excel and CSV files in Laravel applications. It's built on top of PhpSpreadsheet, providing a simple and elegant API to work with spreadsheets.

## Installation

```bash
composer require maatwebsite/excel
```

After installation, publish the configuration file:

```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```

## Basic Usage

### Creating an Export Class

To create an export class, you can use the artisan command:

```bash
php artisan make:export UsersExport --model=User
```

Or create the class manually:

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

### Exporting from a Controller

```php
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

public function export() 
{
    return Excel::download(new UsersExport, 'users.xlsx');
}
```

## Export Interfaces

Laravel Excel provides several interfaces that you can implement to customize your exports:

### FromCollection

The most basic interface that allows you to export a collection of data:

```php
public function collection()
{
    return User::all();
}
```

### WithHeadings

Add column headings to your export:

```php
public function headings(): array
{
    return [
        'ID',
        'Name',
        'Email',
        'Created At'
    ];
}
```

### WithMapping

Map each row of data before exporting:

```php
public function map($user): array
{
    return [
        $user->id,
        $user->name,
        $user->email,
        $user->created_at->format('Y-m-d'),
    ];
}
```

### ShouldAutoSize

Automatically adjust column widths:

```php
class UsersExport implements FromCollection, ShouldAutoSize
{
    // ...
}
```

### WithStyles

Apply styles to cells:

```php
public function styles(Worksheet $sheet)
{
    return [
        // Style the first row as bold text
        1 => ['font' => ['bold' => true]],
    ];
}
```

### WithEvents

Register events to further customize the export:

```php
public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            // Customize the sheet after it's created
        },
    ];
}
```

## Advanced Styling with PhpSpreadsheet

Laravel Excel uses PhpSpreadsheet under the hood, giving you access to all its styling capabilities:

### Setting Cell Values

```php
$event->sheet->getDelegate()->setCellValue('A1', 'Hello World');
```

### Merging Cells

```php
$event->sheet->getDelegate()->mergeCells('A1:E1');
```

### Setting Font

```php
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setName('Vazir');
```

### Setting Alignment

```php
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
```

### Setting Fill Color

```php
$event->sheet->getDelegate()->getStyle('A1:E1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setRGB('E2EFDA');
```

### Setting Text Color

```php
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setColor(
    new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN)
);
```

### Setting RTL Direction (for Persian/Arabic text)

```php
$event->sheet->getDelegate()->setRightToLeft(true);
```

## Practical Examples from Our Project

### 1. Basic Export Class Structure

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
    
    // Other methods...
}
```

### 2. Controller Export Method

```php
public function export(Request $request)
{
    // Build query with filters
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

### 3. Styling Persian Text

```php
public function registerEvents(): array
{
    return [
        AfterSheet::class => function(AfterSheet $event) {
            // Set RTL direction for the entire sheet
            $event->sheet->getDelegate()->setRightToLeft(true);
            
            // Apply right alignment to all cells
            $lastColumn = $event->sheet->getDelegate()->getHighestColumn();
            $lastRow = $event->sheet->getDelegate()->getHighestRow();
            $cellRange = 'A1:' . $lastColumn . $lastRow;
            
            $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
            // Set font for Persian text
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Vazir');
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
        }
    ];
}
```

### 4. Adding a Title Row

```php
// Add title at the top
$event->sheet->getDelegate()->insertNewRowBefore(1, 1);
$event->sheet->getDelegate()->mergeCells('A1:F1');
$event->sheet->getDelegate()->setCellValue('A1', 'گزارش کاربران سیستم');
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
$event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
$event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
```

### 5. Conditional Formatting

```php
// Apply conditional formatting for status
$lastDataRow = $event->sheet->getDelegate()->getHighestRow();
for ($row = 3; $row <= $lastDataRow; $row++) {
    // Status column (D)
    $statusCell = 'D' . $row;
    $status = $event->sheet->getDelegate()->getCell($statusCell)->getValue();
    
    if ($status === 'فعال') {
        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('C6EFCE'); // Green for active
    } else {
        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFC7CE'); // Red for inactive
    }
}
```

## Export File Types

Laravel Excel supports various file types:

```php
// Excel XLSX
return Excel::download(new UsersExport, 'users.xlsx');

// Excel XLS
return Excel::download(new UsersExport, 'users.xls');

// CSV
return Excel::download(new UsersExport, 'users.csv');

// PDF (requires additional setup)
return Excel::download(new UsersExport, 'users.pdf');
```

## Common Issues and Solutions

### 1. Memory Issues with Large Exports

For large exports, use queues:

```php
return Excel::queue(new UsersExport, 'users.xlsx');
```

### 2. Persian/Arabic Text Rendering

- Use RTL direction: `$sheet->setRightToLeft(true)`
- Use compatible fonts like Vazir: `$sheet->getStyle()->getFont()->setName('Vazir')`
- Ensure the font is installed on the server

### 3. Date Formatting

Use the Verta package for Persian dates:

```php
verta($user->created_at)->format('Y/m/d')
```

## Resources

- [Laravel Excel Official Documentation](https://docs.laravel-excel.com/3.1/getting-started/)
- [PhpSpreadsheet Documentation](https://phpspreadsheet.readthedocs.io/)
- [Vazir Font (for Persian text)](https://github.com/rastikerdar/vazir-font)
