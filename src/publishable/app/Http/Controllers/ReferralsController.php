<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user referrals api endpoints.
 */
class ReferralsController extends Controller
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
     * Get list referrals for authorized user.
     * Show all referrals by auth user
     *
     * @return string JSON response list user referrals
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileReferrals()
                ->json()
        );
    }

    /**
     * Get one referral by id for authorized user.
     *
     * @param integer $id Referral id
     * @return string JSON response view referral
     */
    public function show($id)
    {
        dd($this->api()->profileReferral($id)->toUrl());
        return response()->json(
            $this->api()
                ->profileReferral($id)
                ->json()
        );
    }
}
