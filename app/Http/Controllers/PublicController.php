<?php

namespace App\Http\Controllers;

use App\Models\CustomerShareToken;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function customerCard(string $token)
    {
        $shareToken = CustomerShareToken::with('customer')
            ->where('token', $token)
            ->first();

        if (! $shareToken || $shareToken->isExpired()) {
            return view('public.customer-card-expired');
        }

        $customer = $shareToken->customer;

        if (!$customer) {
            abort(404);
        }

        return view('public.customer-card', compact('customer', 'shareToken'));
    }
}
