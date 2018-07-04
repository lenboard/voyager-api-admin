<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user investment earnings api endpoints.
 */
class InvestmentEarningsController extends Controller
{
    use UsesApi;

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get list investment earnings for authorized user.
     *
     * @return string JSON response list user investment earnings
     */
    /*public function index()
    {
        return response()->json(
            $this->api()
                ->profileInvestmentEarningsList()
                ->toUrl()
        );
    }*/

    /**
     * Get one investment earning by id for authorized user.
     *
     * @param integer $id Investment earning id
     * @return string JSON response view investments earning
     */
    public function show($id)
    {
        dd($this->api()->profileInvestmentEarning($id)->toUrl());
        return response()->json(
            $this->api()
                ->profileInvestmentEarning($id)
                ->json()
        );
    }

    /**
     * Get list investment earnings earnings.
     *
     * @return string JSON response list investment_earnings/earnings
     */
    /*public function earnings()
    {
        $user = Auth::user();

        return response()->json(
            $this->api()
                ->profileInvestmentEarnings()
                ->json()
        );
    }*/
}
