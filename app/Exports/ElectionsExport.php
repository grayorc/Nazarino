<?php

namespace App\Exports;

use App\Models\Election;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection;

class ElectionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $elections;

    public function __construct($elections = null)
    {
        $this->elections = $elections;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->elections ?: Election::with(['user', 'options'])->get();
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
            'عمومی/خصوصی',
            'تعداد شرکت‌کنندگان',
            'تعداد آرا',
            'تاریخ پایان',
            'تاریخ ایجاد',
            'ایجاد کننده',
            'گزینه‌ها',
            'آمار گزینه‌ها'
        ];
    }

    /**
     * @param mixed $election
     * @return array
     */
    public function map($election): array
    {
        $optionStats = $election->getDetailedVoteStats();

        $statsString = collect($optionStats)->map(function ($option) {
            $percentage = $option['total_votes'] > 0
                ? round(($option['upvotes'] / $option['total_votes']) * 100)
                : 0;
            return $option['title'] . ': ' . $option['total_votes'] . ' رای (' . $percentage . '%)';
        })->implode(' | ');

        return [
            $election->id,
            $election->title,
            $election->description,
            $election->is_open ? 'باز' : 'بسته',
            $election->is_public ? 'عمومی' : 'خصوصی',
            $election->userCount(),
            $election->getTotalVotes(),
            $election->end_date ? verta($election->end_date)->format('Y/m/d') : 'نامشخص',
            verta($election->created_at)->format('Y/m/d'),
            $election->user->name,
            $election->options->pluck('title')->implode(' | '),
            $statsString
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
            },
        ];
    }
}
