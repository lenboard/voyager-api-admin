<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user investment plans api endpoints.
 */
class InvestmentPlansController extends Controller
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
     * Get list investment plans for authorized user.
     *
     * @return string JSON response list user investment plans
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileInvestmentPlans()
                ->json()
        );
    }

    public function publicInvestmentPlans()
    {
        $investmentPlans = $this->api()
            ->publicInvestmentPlans()
            ->get();

        return view('api.investment-plans.public_investment_plans', compact('investmentPlans'));
    }

    /**
     * Get one investment plan by id for authorized user.
     *
     * @param integer $id Investment plan id
     * @return string JSON response view investment plan
     */
    public function show($id)
    {
        //dd($this->api()->profileInvestmentPlan($id)->toUrl());
        return response()->json(
            $this->api()
                ->profileInvestmentPlan($id)
                ->json()
        );
    }
}
