<?php

namespace App\Exports;

use App\Models\Role;
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

class RoleExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $roles;

    public function __construct($roles = null)
    {
        $this->roles = $roles ?: Role::with('permissions')->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->roles;
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
            'تعداد دسترسی‌ها',
            'دسترسی‌ها',
            'تعداد کاربران',
            'تاریخ ایجاد',
            'تاریخ بروزرسانی'
        ];
    }

    /**
     * @param mixed $role
     * @return array
     */
    public function map($role): array
    {
        return [
            $role->id,
            $role->name,
            $role->display_name,
            $role->description ?: 'بدون توضیحات',
            $role->permissions->count(),
            $role->permissions->pluck('display_name')->implode(' | '),
            $role->users->count(),
            verta($role->created_at)->format('Y/m/d'),
            verta($role->updated_at)->format('Y/m/d')
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
                $event->sheet->getDelegate()->mergeCells('A1:I1');
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش نقش‌های سیستم');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:I2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');
            },
        ];
    }
}
