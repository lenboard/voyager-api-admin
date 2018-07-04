<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user referral earnings api endpoints.
 */
class ReferralEarningsController extends Controller
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
     * Get list referral earnings for authorized user.
     *
     * @return string JSON response list user referral earnings
     */
    public function index()
    {
        dd($this->api()->profileReferralEarningsList()->toUrl());
        return response()->json(
            $this->api()
                ->profileReferralEarningsList()
                ->json()
        );
    }

    /**
     * Get one referral earning by id for authorized user.
     *
     * @param integer $id Referral earning id
     * @return string JSON response view referral earning
     */
    public function show($id)
    {
        dd($this->api()->profileReferralEarning($id)->toUrl());
        return response()->json(
            $this->api()
                ->profileReferralEarning($id)
                ->json()
        );
    }

    /**
     * Get list referral earnings earnings.
     *
     * @return string JSON response list referral_earnings/earnings
     */
    /*public function earnings()
    {
        return response()->json(
            $this->api()
                ->profileReferralEarnings()
                ->json()
        );
    }*/
}
