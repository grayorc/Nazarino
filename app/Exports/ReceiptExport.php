<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReceiptExport implements FromCollection, WithHeadings, WithMapping
{
    protected $receipts;

    public function __construct($receipts)
    {
        $this->receipts = $receipts;
    }

    public function collection()
    {
        return $this->receipts;
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'شماره رسید',
            'کاربر',
            'ایمیل کاربر',
            'مبلغ',
            'واحد پول',
            'وضعیت',
            'روش پرداخت',
            'تاریخ پرداخت',
            'تاریخ ایجاد',
        ];
    }

    public function map($receipt): array
    {
        return [
            $receipt->id,
            $receipt->receipt_number,
            $receipt->user ? $receipt->user->name : 'نامشخص',
            $receipt->user ? $receipt->user->email : 'نامشخص',
            $receipt->amount,
            $receipt->currency,
            $this->getStatus($receipt->status),
            $receipt->payment_method,
            $receipt->paid_at ? verta($receipt->paid_at)->format('Y/m/d H:i') : 'نامشخص',
            verta($receipt->created_at)->format('Y/m/d H:i'),
        ];
    }

    private function getStatus($status)
    {
        switch ($status) {
            case 'completed':
                return 'تکمیل شده';
            case 'pending':
                return 'در انتظار';
            case 'failed':
                return 'ناموفق';
            default:
                return $status;
        }
    }
}
