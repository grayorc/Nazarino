<?php

namespace App\Exports;

use App\Models\SubscriptionTier;
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

class SubscriptionTierExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $subscriptionTiers;

    public function __construct($subscriptionTiers = null)
    {
        $this->subscriptionTiers = $subscriptionTiers ?: SubscriptionTier::with(['subFeatures', 'subscriptionUsers'])->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->subscriptionTiers;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه',
            'عنوان',
            'قیمت (تومان)',
            'تعداد ویژگی‌ها',
            'ویژگی‌های اشتراک',
            'تعداد کاربران',
            'تاریخ ایجاد',
            'تاریخ بروزرسانی'
        ];
    }

    /**
     * @param mixed $subscriptionTier
     * @return array
     */
    public function map($subscriptionTier): array
    {
        return [
            $subscriptionTier->id,
            $subscriptionTier->title,
            number_format($subscriptionTier->price),
            $subscriptionTier->subFeatures->count(),
            $subscriptionTier->subFeatures->pluck('name')->implode(' | '),
            $subscriptionTier->subscriptionUsers->count(),
            verta($subscriptionTier->created_at)->format('Y/m/d'),
            verta($subscriptionTier->updated_at)->format('Y/m/d')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
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
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش سطوح اشتراک');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:H2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                $lastDataRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 3; $row <= $lastDataRow; $row++) {
                    $priceCell = 'C' . $row;
                    $event->sheet->getDelegate()->getStyle($priceCell)->getFont()->setColor(
                        new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN)
                    );
                }
            },
        ];
    }
}
