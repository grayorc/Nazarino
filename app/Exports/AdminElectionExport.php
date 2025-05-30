<?php

namespace App\Exports;

use App\Models\Election;
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

class AdminElectionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $elections;

    public function __construct($elections = null)
    {
        $this->elections = $elections ?: Election::with(['user', 'options'])->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->elections;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه',
            'عنوان',
            'توضیحات',
            'وضعیت',
            'نوع نظرسنجی',
            'تعداد شرکت‌کنندگان',
            'تعداد آرا',
            'تعداد گزینه‌ها',
            'تعداد نظرات',
            'تاریخ پایان',
            'تاریخ ایجاد',
            'ایجاد کننده'
        ];
    }

    /**
     * @param mixed $election
     * @return array
     */
    public function map($election): array
    {
        $status = $election->is_open ? 'باز' : 'بسته';
        $type = $election->is_public ? 'عمومی' : 'خصوصی';
        $endDate = 'نامحدود';

        if ($election->end_date) {
            if ($election->end_date > now()) {
                $endDate = verta($election->end_date)->format('Y/m/d');
            } else {
                $endDate = 'پایان یافته';
            }
        }

        return [
            $election->id,
            $election->title,
            $election->description ?: 'بدون توضیحات',
            $status,
            $type,
            $election->userCount() ?: 0,
            $election->getTotalVotes(),
            $election->options->count(),
            $election->getTotalComments(),
            $endDate,
            verta($election->created_at)->format('Y/m/d'),
            $election->user->name
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
                $event->sheet->getDelegate()->mergeCells('A1:L1');
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش نظرسنجی‌های سیستم');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:L2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                $lastDataRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 3; $row <= $lastDataRow; $row++) {
                    $statusCell = 'D' . $row;
                    $status = $event->sheet->getDelegate()->getCell($statusCell)->getValue();

                    if ($status === 'باز') {
                        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE'); // Green for open
                    } else {
                        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE'); // Red for closed
                    }

                    $endDateCell = 'J' . $row;
                    $endDate = $event->sheet->getDelegate()->getCell($endDateCell)->getValue();

                    if ($endDate === 'پایان یافته') {
                        $event->sheet->getDelegate()->getStyle($endDateCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE');
                    } else if ($endDate === 'نامحدود') {
                        $event->sheet->getDelegate()->getStyle($endDateCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFEB9C');
                    }
                }
            },
        ];
    }
}
