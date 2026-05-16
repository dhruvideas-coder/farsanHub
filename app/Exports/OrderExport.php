<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithEvents
{
    protected $customerId;
    protected $monthYear;
    protected $totalOrderAmount = 0;
    protected $totalOrderQuantity = 0;
    protected $totalKg = 0;
    protected $totalNang = 0;
    protected $rowCount = 0;

    public function __construct($customerId = null, $monthYear = null)
    {
        $this->customerId = $customerId;
        $this->monthYear = $monthYear;
    }

    public function collection()
    {
        $query = Order::with(['product', 'customer'])
            ->where('user_id', auth()->id());

        // Filter by customer if provided
        if ($this->customerId) {
            $query->where('orders.customer_id', $this->customerId);
        }

        // Filter by month-year if provided
        if ($this->monthYear) {
            $start = Carbon::parse($this->monthYear . '-01')->startOfMonth()->toDateString();
            $end = Carbon::parse($this->monthYear . '-01')->endOfMonth()->toDateString();

            $query->where(function ($q) use ($start, $end) {
                $q->whereBetween('orders.order_date', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->whereNull('orders.order_date')
                         ->whereDate('orders.created_at', '>=', $start)
                         ->whereDate('orders.created_at', '<=', $end);
                  });
            });

            Log::info('Export Date Range: ' . $start . ' to ' . $end);
        }

        $orders = $query->orderBy('orders.created_at', 'desc')->get();
        $this->rowCount = $orders->count();
        Log::info('Orders record count: ' . $this->rowCount);

        $srNo = 1;
        return $orders->map(function ($item) use (&$srNo) {
            $totalAmount = $item->order_quantity * $item->order_price;
            $this->totalOrderAmount += $totalAmount;
            $this->totalOrderQuantity += $item->order_quantity;

            $unit = strtolower($item->product->unit ?? 'kg');
            if ($unit === 'nang') {
                $this->totalNang += $item->order_quantity;
            } else {
                $this->totalKg += $item->order_quantity;
            }

            return [
                'sr_no'         => $srNo++,
                'customer_name' => $item->customer->customer_name ?? '-',
                'shop_name'     => $item->customer->shop_name ?? '-',
                'product_name'  => $item->product->product_name ?? '-',
                'order_quantity' => ($item->order_quantity ?? '0') . ' ' . trans('portal.' . strtolower($item->product->unit ?? 'kg')),
                'order_price'   => '₹ ' . $item->order_price ?? '',
                'total_amount'  => '₹ ' . $totalAmount,
                'date'          => $item->order_date ? date('d-m-Y', strtotime($item->order_date)) : ($item->created_at ? date('d-m-Y', strtotime($item->created_at)) : '-'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            trans('portal.customer_name'),
            trans('portal.shop_name'),
            trans('portal.product_name'),
            trans('portal.order_quantity'),
            trans('portal.order_price'),
            trans('portal.amount'),
            trans('portal.date'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $totalRow = $this->rowCount + 2;
        $sheet->getStyle('A' . $totalRow . ':J' . $totalRow)->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $totalRow = $this->rowCount + 2;

                $event->sheet->setCellValue('A' . $totalRow, 'Grand Total');
                $event->sheet->setCellValue('G' . $totalRow, $this->totalKg . ' ' . trans('portal.kg') . ', ' . $this->totalNang . ' ' . trans('portal.nang'));
                $event->sheet->setCellValue('I' . $totalRow, '₹ ' . $this->totalOrderAmount);

                $event->sheet->mergeCells('A' . $totalRow . ':F' . $totalRow);

                $event->sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $event->sheet->getStyle('G' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getStyle('I' . $totalRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            },
        ];
    }
}
