<?php

namespace App\Exports;

use App\Models\User;
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

class UserExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $users;

    public function __construct($users = null)
    {
        $this->users = $users ?: User::with(['roles', 'subscriptions', 'elections'])->get();
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'شناسه',
            'نام کاربری',
            'نام',
            'نام خانوادگی',
            'ایمیل',
            'وضعیت ایمیل',
            'شماره تلفن',
            'نقش‌ها',
            'تعداد نظرسنجی‌ها',
            'نظرسنجی‌های فعال',
            'نظرسنجی‌های غیرفعال',
            'وضعیت اشتراک',
            'روزهای باقیمانده اشتراک',
            'تعداد دنبال‌کنندگان',
            'تعداد دنبال‌شوندگان',
            'تاریخ عضویت'
        ];
    }

    /**
     * @param mixed $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->username,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->email_verified_at ? 'تایید شده' : 'تایید نشده',
            $user->phone_number ?: 'ثبت نشده',
            $user->roles->pluck('name')->implode(' | '),
            $user->totalElections(),
            $user->totalActiveElections(),
            $user->totalInactiveElections(),
            $user->hasActiveSubscriptionTier() ? 'فعال' : 'غیرفعال',
            $user->hasActiveSubscriptionTier() ? $user->getRemainingDays() : '0',
            $user->countFollowers(),
            $user->countFollowings(),
            verta($user->created_at)->format('Y/m/d')
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
                $event->sheet->getDelegate()->mergeCells('A1:P1');
                $event->sheet->getDelegate()->setCellValue('A1', 'گزارش کاربران سیستم');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A2:P2')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E2EFDA');

                $lastDataRow = $event->sheet->getDelegate()->getHighestRow();
                for ($row = 3; $row <= $lastDataRow; $row++) {
                    $statusCell = 'F' . $row;
                    $status = $event->sheet->getDelegate()->getCell($statusCell)->getValue();

                    if ($status === 'تایید شده') {
                        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE');
                    } else {
                        $event->sheet->getDelegate()->getStyle($statusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE');
                    }

                    $subStatusCell = 'L' . $row;
                    $subStatus = $event->sheet->getDelegate()->getCell($subStatusCell)->getValue();

                    if ($subStatus === 'فعال') {
                        $event->sheet->getDelegate()->getStyle($subStatusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('C6EFCE');
                    } else {
                        $event->sheet->getDelegate()->getStyle($subStatusCell)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('FFC7CE');
                    }
                }
            },
        ];
    }
}
