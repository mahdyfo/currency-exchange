<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Currency extends Model
{
    use HasFactory;

    protected $casts = ['rate' => 'double'];

    public function convert($sourceSymbol, $targetSymbol, $amount)
    {
        // Cache rates and do not request api everytime
        if (Cache::get('last_rates_update_time', 0) < now()->subMinutes(env('CURRENCY_RATES_CACHE'))->unix()) {
            $this->updateRates();
            Cache::set('last_rates_update_time', time());
        }

        // Get currencies info from db
        $currencies = Currency::whereIn('symbol', [$sourceSymbol, $targetSymbol])->get();

        // Rates
        $sourceCurrency = $currencies->where('symbol', $sourceSymbol)->first();
        $targetCurrency = $currencies->where('symbol', $targetSymbol)->first();

        return $amount * $targetCurrency->rate / $sourceCurrency->rate;
    }

    public function updateRates()
    {
        $allSymbols = Currency::all()->pluck('symbol')->toArray();

        $response = Http::get(env('CURRENCY_API_URL') . '/' . env('CURRENCY_API_KEY') . '/rates', [
            'source' => 'USD',
            'target' => implode(',', $allSymbols)
        ])->body();

        if ($response) {
            $response = json_decode($response);

            if (!empty($response->rates)) {
                foreach ($response->rates as $symbol => $rate) {
                    Currency::where('symbol', $symbol)->update(['rate' => $rate]);
                }
            }
        }

        return true;
    }
}
