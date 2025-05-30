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

class AdminElectionDetailedExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $election;
    protected $voteStats;

    public function __construct(Election $election)
    {
        $this->election = $election;
        $this->voteStats = $election->getDetailedVoteStats();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->voteStats;
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
            'درصد از کل آرا',
        ];
    }

    /**
     * @param mixed $option
     * @return array
     */
    public function map($option): array
    {
        $totalVotes = $this->election->getTotalVotes();
        $percentage = $totalVotes > 0 ? round(($option['total_votes'] / $totalVotes) * 100, 2) : 0;

        return [
            $option['option_id'],
            $option['title'],
            $option['total_votes'],
            $option['upvotes'],
            $option['downvotes'],
            $percentage . '%',
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
                $event->sheet->getDelegate()->mergeCells('A1:F1');
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش تفصیلی نظرسنجی: ' . $this->election->title);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->mergeCells('A2:F2');
                $status = $this->election->is_open ? 'باز' : 'بسته';
                $type = $this->election->is_public ? 'عمومی' : 'خصوصی';
                $info = "وضعیت: {$status} | نوع: {$type} | تعداد کل آرا: {$this->election->getTotalVotes()} | تاریخ ایجاد: " . verta($this->election->created_at)->format('Y/m/d');
                $event->sheet->getDelegate()->setCellValue('A2', $info);
                $event->sheet->getDelegate()->getStyle('A2')->getFont()->setSize(11);
                $event->sheet->getDelegate()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A3:F3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                $lastDataRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 4; $row <= $lastDataRow; $row++) {
                    $percentCell = 'F' . $row;
                    $percent = (float) $event->sheet->getDelegate()->getCell($percentCell)->getValue();

                    if ($percent >= 50) {
                        $event->sheet->getDelegate()->getStyle($percentCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE');
                    } else if ($percent >= 25) {
                        $event->sheet->getDelegate()->getStyle($percentCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFEB9C');
                    } else {
                        $event->sheet->getDelegate()->getStyle($percentCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE');
                    }
                }
            },
        ];
    }
}
