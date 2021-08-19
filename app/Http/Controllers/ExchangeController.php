<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConvertRequest;
use App\Models\Currency;

class ExchangeController extends Controller
{
    public function index()
    {
        $currencies = Currency::select(
            'id',
            'name',
            'symbol',
            'rate'
        )->get();

        return view('exchange', compact('currencies'));
    }

    public function convert(CurrencyConvertRequest $request)
    {
        // Get currencies from db
        $currency = new Currency;

        $result = $currency->convert(
            $request->input('source'),
            $request->input('target'),
            $request->input('amount')
        );

        return ['result' => $result];
    }
}
