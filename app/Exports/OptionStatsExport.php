<?php

namespace App\Exports;

use App\Models\Election;
use App\Models\Option;
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

class OptionStatsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $election;

    public function __construct(Election $election)
    {
        $this->election = $election;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // Get all options with their votes
        return $this->election->options()->with('votes')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه گزینه',
            'عنوان گزینه',
            'تعداد کل آرا',
            'آرای مثبت',
            'آرای منفی',
            'درصد آرای مثبت',
            'تعداد نظرات'
        ];
    }

    /**
     * @param mixed $option
     * @return array
     */
    public function map($option): array
    {
        $totalVotes = $option->votes->count();
        $upvotes = $option->votes->where('vote', 1)->count();
        $downvotes = $option->votes->where('vote', -1)->count();
        $upvotePercentage = $totalVotes > 0 ? round(($upvotes / $totalVotes) * 100, 1) : 0;
        $commentCount = $option->comments ? $option->comments->count() : 0;

        return [
            $option->id,
            $option->title,
            $totalVotes,
            $upvotes,
            $downvotes,
            $upvotePercentage . '%',
            $commentCount
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

                $event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->setCellValue('A1', 'آمار گزینه‌های نظرسنجی: ' . $this->election->title);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);

                // Add election summary info
                $event->sheet->getDelegate()->mergeCells('A2:G2');
                $event->sheet->getDelegate()->setCellValue('A2', 'تعداد کل آرا: ' . $this->election->getTotalVotes() .
                    ' | تعداد شرکت‌کنندگان: ' . $this->election->userCount() .
                    ' | تاریخ ایجاد: ' . verta($this->election->created_at)->format('Y/m/d'));
                $event->sheet->getDelegate()->getStyle('A2')->getFont()->setBold(true);

                // Style the header cells
                $event->sheet->getDelegate()->getStyle('A3:G3')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A3:G3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                $lastDataRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 4; $row <= $lastDataRow; $row++) {
                    $percentCell = 'F' . $row;
                    $percentValue = $event->sheet->getDelegate()->getCell($percentCell)->getValue();
                    $percentValue = (float) str_replace('%', '', $percentValue);

                    if ($percentValue >= 70) {
                        $event->sheet->getDelegate()->getStyle($percentCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE');
                    } elseif ($percentValue <= 30) {
                        $event->sheet->getDelegate()->getStyle($percentCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE');
                    } elseif ($percentValue > 30 && $percentValue < 70) {
                        $event->sheet->getDelegate()->getStyle($percentCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFEB9C');
                    }
                }
            },
        ];
    }
}
