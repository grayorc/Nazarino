<?php

namespace App\Exports;

use App\Models\Permission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class PermissionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $permissions;

    public function __construct($permissions = null)
    {
        $this->permissions = $permissions ?: Permission::with('roles')->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->permissions;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه',
            'نام',
            'نام نمایشی',
            'توضیحات',
            'تعداد نقش‌ها',
            'نقش‌های مرتبط',
            'تاریخ ایجاد',
            'تاریخ بروزرسانی'
        ];
    }

    /**
     * @param mixed $permission
     * @return array
     */
    public function map($permission): array
    {
        return [
            $permission->id,
            $permission->name,
            $permission->display_name,
            $permission->description ?: 'بدون توضیحات',
            $permission->roles->count(),
            $permission->roles->pluck('display_name')->implode(' | '),
            verta($permission->created_at)->format('Y/m/d'),
            verta($permission->updated_at)->format('Y/m/d')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);

                $lastColumn = $event->sheet->getDelegate()->getHighestColumn();
                $lastRow = $event->sheet->getDelegate()->getHighestRow();
                $cellRange = 'A1:' . $lastColumn . $lastRow;

                $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setName('Vazir');
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);

                $event->sheet->getDelegate()->insertNewRowBefore(1, 1);
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش دسترسی‌های سیستم');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');
            },
        ];
    }
}
