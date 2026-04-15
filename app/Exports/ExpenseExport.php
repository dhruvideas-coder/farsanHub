<?php

namespace App\Exports;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting, WithEvents
{
    public $monthYear;
    protected $total = 0;

    public function __construct($monthYear)
    {
        $this->monthYear = $monthYear;
    }

    public function collection()
    {
        $start = Carbon::parse($this->monthYear . '-01')->startOfMonth();
        $end   = Carbon::parse($this->monthYear . '-01')->endOfMonth();

        Log::info('Export Start Date: ' . $start);
        Log::info('Export End Date: ' . $end);

        $expenses = Expense::where('user_id', auth()->id())
            ->whereBetween('created_at', [$start, $end])
            ->get();

        Log::info('Expense record count: ' . $expenses->count());

        return $expenses->map(function ($item) {
            $amount = (float) ($item->amount ?? 0);
            $this->total += $amount;

            return [
                'purpose' => $item->purpose ?? '-',
                'amount'  => $amount,
                'comment' => $item->comment ?? '-',
                'date'    => $item->created_at ? date('d-m-Y', strtotime($item->created_at)) : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            trans('portal.purpose'),
            trans('portal.amount'),
            trans('portal.comment'),
            trans('portal.date'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
    }

    public function columnFormats(): array
    {
        return [
            'B' => '#,##0.00',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $lastRow  = $sheet->getHighestRow();
                $totalRow = $lastRow + 1;

                // Write PHP-calculated total directly — no formula
                $sheet->setCellValue('A' . $totalRow, 'Total');
                $sheet->setCellValue('B' . $totalRow, $this->total);

                // Bold the total row
                $sheet->getStyle('A' . $totalRow . ':D' . $totalRow)
                    ->getFont()->setBold(true);

                // Light yellow background
                $sheet->getStyle('A' . $totalRow . ':D' . $totalRow)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFFF3CD');

                // Number format on total amount cell
                $sheet->getStyle('B' . $totalRow)
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            },
        ];
    }
}
