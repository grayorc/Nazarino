<?php

namespace App\Exports;

use App\Models\SubscriptionUser;
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

class SubscriptionUserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $subscriptionUsers;

    public function __construct($subscriptionUsers = null)
    {
        $this->subscriptionUsers = $subscriptionUsers ?: SubscriptionUser::with(['user', 'subscriptionTier'])->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->subscriptionUsers;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه',
            'نام کاربر',
            'ایمیل کاربر',
            'نوع اشتراک',
            'قیمت اشتراک',
            'تاریخ شروع',
            'تاریخ پایان',
            'وضعیت',
            'روزهای باقیمانده',
            'شماره تراکنش',
            'تاریخ ایجاد'
        ];
    }

    /**
     * @param mixed $subscriptionUser
     * @return array
     */
    public function map($subscriptionUser): array
    {
        $status = 'نامشخص';
        $remainingDays = 0;

        if ($subscriptionUser->end_date) {
            if ($subscriptionUser->end_date > now()) {
                $status = 'فعال';
                $remainingDays = now()->diffInDays($subscriptionUser->end_date);
            } else {
                $status = 'منقضی شده';
            }
        }

        return [
            $subscriptionUser->id,
            $subscriptionUser->user->name,
            $subscriptionUser->user->email,
            $subscriptionUser->subscriptionTier->title,
            number_format($subscriptionUser->subscriptionTier->price) . ' تومان',
            $subscriptionUser->start_date ? verta($subscriptionUser->start_date)->format('Y/m/d') : 'نامشخص',
            $subscriptionUser->end_date ? verta($subscriptionUser->end_date)->format('Y/m/d') : 'نامحدود',
            $status,
            $remainingDays,
            $subscriptionUser->transaction_id ?: 'ندارد',
            verta($subscriptionUser->created_at)->format('Y/m/d')
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
                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش کاربران اشتراکی سیستم');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:K2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                $lastDataRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 3; $row <= $lastDataRow; $row++) {
                    $statusCell = 'H' . $row;
                    $status = $event->sheet->getDelegate()->getCell($statusCell)->getValue();

                    if ($status === 'فعال') {
                        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE'); // Green for active
                    } else if ($status === 'منقضی شده') {
                        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE'); // Red for expired
                    }

                    $daysCell = 'I' . $row;
                    $days = (int)$event->sheet->getDelegate()->getCell($daysCell)->getValue();

                    if ($days > 30) {
                        $event->sheet->getDelegate()->getStyle($daysCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE');
                    } else if ($days > 7) {
                        $event->sheet->getDelegate()->getStyle($daysCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFEB9C');
                    } else if ($days > 0) {
                        $event->sheet->getDelegate()->getStyle($daysCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE');
                    }
                }
            },
        ];
    }
}
