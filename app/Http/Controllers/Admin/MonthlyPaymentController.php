<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Customer wise payment entry for a month: the customers who ordered in that
 * month are listed, payment date / account / transaction id are typed in here,
 * and the saved sheet is exported as a PDF for the accountant.
 */
class MonthlyPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        try {
            $monthYear = $request->input('month_year');
            $months    = $this->orderMonths();

            $rows     = [];
            $monthName = null;

            if ($monthYear) {
                $rows      = $this->rowsForMonth($monthYear);
                $monthName = Carbon::parse($monthYear . '-01')->format('F Y');

                if (empty($rows)) {
                    return redirect()->route('admin.monthly-payment.index')
                        ->with('error', __('portal.no_customer_summary_data'));
                }
            }

            return view('admin.monthly-payment.index', [
                'months'       => $months,
                'monthYear'    => $monthYear,
                'monthName'    => $monthName,
                'rows'         => $rows,
                'hasSavedData' => $this->hasSavedData($rows),
                'accounts'     => $this->accountSuggestions(),
            ]);
        } catch (\Throwable $th) {
            Log::error('MonthlyPaymentController@index Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Save every submitted row for the month. A row with all three fields blank
     * is removed, so clearing a line and saving undoes it.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'month_year'       => 'required|date_format:Y-m',
                'payment_date'     => 'required|array',
                'payment_date.*'   => 'nullable|date',
                'payment_amount'   => 'required|array',
                'payment_amount.*' => 'nullable|numeric|min:0',
                'account'          => 'required|array',
                'account.*'        => 'nullable|string|max:255',
                'transaction_id'   => 'required|array',
                'transaction_id.*' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $monthYear = $request->input('month_year');
            $userId    = auth()->id();

            // Only the customers the entry screen listed may be written to. This has to be
            // the same rule the screen uses (customers on this user's orders that month) —
            // filtering by customer ownership instead silently drops rows, because an order
            // of this user can point at a customer record owned by another user.
            $submittedIds = array_map('intval', array_keys($request->input('payment_date', [])));
            $allowedIds   = $this->customerIdsForMonth($monthYear)->intersect($submittedIds);

            DB::transaction(function () use ($request, $monthYear, $userId, $allowedIds) {
                foreach ($allowedIds as $customerId) {
                    $paymentDate   = $request->input("payment_date.{$customerId}") ?: null;
                    $amount        = $request->input("payment_amount.{$customerId}");
                    $amount        = ($amount === null || $amount === '') ? null : (float) $amount;
                    $account       = trim((string) $request->input("account.{$customerId}")) ?: null;
                    $transactionId = trim((string) $request->input("transaction_id.{$customerId}")) ?: null;

                    $keys = [
                        'user_id'     => $userId,
                        'customer_id' => $customerId,
                        'month_year'  => $monthYear,
                    ];

                    if (!$paymentDate && $amount === null && !$account && !$transactionId) {
                        CustomerPayment::where($keys)->delete();
                        continue;
                    }

                    CustomerPayment::updateOrCreate($keys, [
                        'payment_date'   => $paymentDate,
                        'payment_amount' => $amount,
                        'account'        => $account,
                        'transaction_id' => $transactionId,
                    ]);
                }
            });

            return redirect()->route('admin.monthly-payment.index', ['month_year' => $monthYear])
                ->with('success', __('portal.monthly_payment_saved'));
        } catch (\Throwable $th) {
            Log::error('MonthlyPaymentController@store Error: ' . $th->getMessage());
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /** The saved sheet as a PDF for the accountant. */
    public function pdf(Request $request)
    {
        try {
            $monthYear = $request->input('month_year');

            if (!$monthYear) {
                return redirect()->back()->with('error', __('portal.select_month_year_error'));
            }

            $rows = $this->rowsForMonth($monthYear);

            if (empty($rows)) {
                return redirect()->back()->with('error', __('portal.no_customer_summary_data'));
            }

            // Nothing entered yet — the sheet would print as an empty grid
            if (!$this->hasSavedData($rows)) {
                return redirect()->route('admin.monthly-payment.index', ['month_year' => $monthYear])
                    ->with('error', __('portal.no_payment_data_to_export'));
            }

            // Force English locale for report generation (DomPDF's bundled font has no Gujarati glyphs)
            $previousLocale = app()->getLocale();
            app()->setLocale('en');

            try {
                $data = [
                    'rows'        => $rows,
                    'amountTotal' => $this->amountTotal($rows),
                    'monthYear'   => $monthYear,
                    'monthName'   => Carbon::parse($monthYear . '-01')->format('F Y'),
                    'reportDate'  => now()->format('d M Y, h:i A'),
                    'logoPath'    => public_path('images/logo.png'),
                ];

                $pdf = \PDF::loadView('admin.monthly-payment.pdf', $data);
                $pdf->setPaper('A4', 'portrait');

                $fileName = 'Customer-Payment-Sheet-' . Carbon::parse($monthYear . '-01')->format('F-Y') . '.pdf';

                // Readable-by-JS marker (httpOnly off) so the entry screen can drop its
                // loader the moment the file is actually on its way, not on a guessed timer.
                return $pdf->download($fileName)
                    ->withCookie(cookie('pdf_ready', '1', 1, null, null, null, false));
            } finally {
                app()->setLocale($previousLocale);
            }
        } catch (\Throwable $th) {
            Log::error('MonthlyPaymentController@pdf Error: ' . $th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Customers who ordered in the given month, each with their saved payment
     * entry (blank when nothing has been entered yet). Sorted by shop, then name.
     */
    private function rowsForMonth(string $monthYear): array
    {
        $userId      = auth()->id();
        $customerIds = $this->customerIdsForMonth($monthYear);

        if ($customerIds->isEmpty()) {
            return [];
        }

        $payments = CustomerPayment::where('user_id', $userId)
            ->where('month_year', $monthYear)
            ->whereIn('customer_id', $customerIds)
            ->get()
            ->keyBy('customer_id');

        return Customer::whereIn('id', $customerIds)
            ->get()
            ->map(function ($customer) use ($payments) {
                $payment = $payments->get($customer->id);

                return [
                    'customer_id'    => $customer->id,
                    'shop_name'      => $this->englishName($customer, 'shop_name'),
                    'customer_name'  => $this->englishName($customer, 'customer_name'),
                    'payment_date'   => $payment && $payment->payment_date
                        ? $payment->payment_date->format('Y-m-d')
                        : '',
                    'payment_amount' => $payment && $payment->payment_amount !== null
                        ? (float) $payment->payment_amount
                        : null,
                    'account'        => $payment->account ?? '',
                    'transaction_id' => $payment->transaction_id ?? '',
                ];
            })
            ->sortBy(function ($row) {
                return [$row['shop_name'], $row['customer_name']];
            })
            ->values()
            ->all();
    }

    /**
     * The customers this user ordered from in the given month. Single source of truth
     * for both listing the entry rows and deciding which rows may be saved.
     */
    private function customerIdsForMonth(string $monthYear)
    {
        return Order::where('user_id', auth()->id())
            ->whereRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') = ?", [$monthYear])
            ->distinct()
            ->pluck('customer_id');
    }

    /** True when at least one row of the month has something saved in it. */
    private function hasSavedData(array $rows): bool
    {
        foreach ($rows as $row) {
            if ($row['payment_date'] || $row['payment_amount'] !== null || $row['account'] || $row['transaction_id']) {
                return true;
            }
        }

        return false;
    }

    /** Sum of the entered amounts, for the PDF's total row. */
    private function amountTotal(array $rows): float
    {
        return array_sum(array_column($rows, 'payment_amount'));
    }

    /** Months that have orders, newest first, for the month dropdown. */
    private function orderMonths()
    {
        return Order::selectRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') as value, DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%M-%Y') as label")
            ->where('user_id', auth()->id())
            ->groupByRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m'), DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%M-%Y')")
            ->orderByRaw("DATE_FORMAT(COALESCE(order_date, DATE(created_at)), '%Y-%m') DESC")
            ->get();
    }

    /** Account names already used by this user, for the input's autocomplete list. */
    private function accountSuggestions()
    {
        return CustomerPayment::where('user_id', auth()->id())
            ->whereNotNull('account')
            ->select('account')->distinct()
            ->orderBy('account')->pluck('account');
    }

    /**
     * Read a translatable attribute in English, whatever the current locale is.
     * Falls back to the locale-resolved value when no English translation exists.
     */
    private function englishName(?Customer $model, string $attribute): string
    {
        if (!$model) {
            return '';
        }

        return $model->getTranslation($attribute, 'en') ?: (string) ($model->{$attribute} ?? '');
    }
}
