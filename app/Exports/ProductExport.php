<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProductExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        // Fetch all products with their base prices
        $products = Product::where('user_id', auth()->id())
            ->orderBy('product_name')
            ->get();

        // Fetch all customer-specific prices for this user
        $prices = ProductPrice::with(['customer', 'product'])
            ->where('user_id', auth()->id())
            ->get()
            ->sortBy(function($price) {
                return $price->product->product_name;
            })
            ->groupBy('product_id');

        $rows = collect();
        $srNo = 1;

        foreach ($products as $product) {
            $customerPrices = $prices->get($product->id, collect());

            if ($customerPrices->isEmpty()) {
                // Product with no custom pricing — show base price row only
                $rows->push([
                    'sr_no'          => $srNo++,
                    'product_name'   => $product->product_name,
                    'unit'           => $product->unit ?? '-',
                    'base_price'     => $product->product_base_price,
                    'customer_name'  => '—',
                    'customer_price' => '—',
                ]);
            } else {
                // First row: product info + first customer price
                $first = $customerPrices->first();
                $rows->push([
                    'sr_no'          => $srNo++,
                    'product_name'   => $product->product_name,
                    'unit'           => $product->unit ?? '-',
                    'base_price'     => $product->product_base_price,
                    'customer_name'  => $first->customer->customer_name . ($first->customer->shop_name ? ' (' . $first->customer->shop_name . ')' : ''),
                    'customer_price' => $first->price,
                ]);

                // Remaining customer rows (no repeated product info)
                foreach ($customerPrices->slice(1) as $cp) {
                    $rows->push([
                        'sr_no'          => '',
                        'product_name'   => '',
                        'unit'           => '',
                        'base_price'     => '',
                        'customer_name'  => $cp->customer->customer_name . ($cp->customer->shop_name ? ' (' . $cp->customer->shop_name . ')' : ''),
                        'customer_price' => $cp->price,
                    ]);
                }
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Sr. No.',
            'Product Name',
            'Unit',
            'Base Price (₹)',
            'Customer',
            'Customer Price (₹)',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 10,
            'D' => 18,
            'E' => 35,
            'F' => 20,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Bold header row
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFCC99');
        $sheet->getStyle('A1:F1')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
