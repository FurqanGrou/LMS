<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public static function getProfit($amount)
    {
        $profitPercent = 10;

        return $amount * $profitPercent / 100;
    }
}
