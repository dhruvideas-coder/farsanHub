<?php

use App\Models\PortalActivities;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Hash;

// Public URL for an image path stored on the "public" disk.
// Some rows hold a full URL instead of a relative path (an old placeholder
// default wrote asset('images/logo.png') straight into the column), so those are
// returned untouched — passing them to Storage::url() would prefix them with
// /storage/ a second time and produce /storage/https://site/images/logo.png.
// Empty values return the $fallback asset, or null so the view's own placeholder
// (initial-letter avatar) takes over.
if (!function_exists('imageUrl')) {
    function imageUrl(?string $path, ?string $fallback = null): ?string
    {
        if (empty($path)) {
            return $fallback ? asset($fallback) : null;
        }

        // Already usable as-is: http(s)://, //cdn, /images/logo.png, data:
        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, '/') || str_starts_with($path, 'data:')) {
            return $path;
        }

        // disk('public') explicitly — the bare Storage::url() would use the default
        // disk from FILESYSTEM_DISK, which differs between local and live
        return Storage::disk('public')->url($path);
    }
}

if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}

// encrypt string 
if (!function_exists('encryptResponse')) {
    function encryptResponse($responseData, $aesKey, $aesIv)
    {
        $encryptedResponse = openssl_encrypt($responseData, 'aes-256-cbc', $aesKey, 0, $aesIv);
        return $encryptedResponse;
    }
}

// decrypt string
if (!function_exists('decryptAesResponse')) {
    function decryptAesResponse($responseData, $aesKey, $aesIv)
    {
        // $encryptedResponse = openssl_encrypt($responseData, 'aes-256-cbc', $aesKey, 0, $aesIv);
        $decryptedResponse = openssl_decrypt(base64_decode($responseData['response']), 'AES-256-CBC', $aesKey, OPENSSL_RAW_DATA, $aesIv);
        return $decryptedResponse;
    }
}

if (!function_exists('plainAmount')) {
    function plainAmount($formattedAmount = 0)
    {
        return preg_replace('/[^\d.]/', '', $formattedAmount);
    }
}
if (!function_exists('formattedAmount')) {
    function formattedAmount($amount = 0)
    {
        return number_format($amount, 2);
    }
}

// Format a recipe quantity for display.
// Weight (kg) and volume (litre) always show 3 decimals so grams/ml stay visible
// (e.g. 1.200 = 1 kg 200 g, 0.200 = 200 g). Piece/Nang trim trailing zeros.
if (!function_exists('formatQty')) {
    function formatQty($qty, $unit = null)
    {
        $qty = (float) $qty;
        if (in_array($unit, ['kg', 'litre'])) {
            return number_format($qty, 3, '.', '');
        }
        return rtrim(rtrim(number_format($qty, 3, '.', ''), '0'), '.');
    }
}

// Human-readable quantity for the requirement report (view + share only).
// kg    -> "1 kg 200 gram" / "200 gram" / "1 kg"
// litre -> "1 litre 200 ml" / "200 ml" / "1 litre"
// piece / Nang / other -> plain number + unit ("5 piece", "20 Nang")
if (!function_exists('formatQtyHuman')) {
    function formatQtyHuman($qty, $unit = null)
    {
        $qty = (float) $qty;

        if ($unit === 'kg' || $unit === 'litre') {
            $big   = $unit === 'kg' ? __('portal.unit_kg')   : __('portal.unit_litre');
            $small = $unit === 'kg' ? __('portal.unit_gram') : __('portal.unit_ml');

            $totalSmall = (int) round($qty * 1000); // grams or millilitres
            $whole      = intdiv($totalSmall, 1000);
            $rem        = $totalSmall % 1000;

            $parts = [];
            if ($whole > 0) {
                $parts[] = $whole . ' ' . $big;
            }
            if ($rem > 0) {
                $parts[] = $rem . ' ' . $small;
            }
            if (empty($parts)) {
                $parts[] = '0 ' . $small;
            }

            return implode(' ', $parts);
        }

        // piece / Nang / other counts
        return formatQty($qty, $unit) . ($unit ? ' ' . $unit : '');
    }
}

// format number by count of groups
if (!function_exists('formatByGroups')) {
    function formatByGroups($number = '', $group = 5)
    {
        if (!empty($number)) {
            return trim(preg_replace('/(\d{' . $group . '})(?=\d)/', '$1 ', $number));
        } else {
            return false;
        }
    }
}
