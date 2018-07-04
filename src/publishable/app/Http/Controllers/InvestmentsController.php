<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;
use App\Support\Classes\Api\Pagination;

/**
 * Controller for user investments api endpoints.
 */
class InvestmentsController extends Controller
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
     * Get list investments for authorized user.
     *
     * @return string JSON response list user investments
     */
    public function index(Request $request)
    {
        return response()->json(
            $this->api()
                ->profileInvestments()
                ->json()
        );

        // с применением верстки

        $investments = $this->api()
            ->profileInvestments()
            ->pagination($request->get('page'), $request->get('perPage'))
            ->get();

        $pagination = new Pagination($request, $investments->pagination);

        return view('api.investments.index', compact('investments', 'pagination'));
    }

    /**
     * Get one investment by id for authorized user.
     *
     * @param integer $id Investment id
     * @return string JSON response view investment
     */
    public function show($id)
    {
        //dd($this->api()->profileInvestment($id)->toUrl());
        return response()->json(
            $this->api()
                ->profileInvestment($id)
                ->json()
        );
    }

    /**
     * Get list investments earnings.
     *
     * @return string JSON response list investments/earnings
     */
    public function earnings()
    {
        //dd($this->api()->profileInvestmentsEarnings()->toUrl());
        return response()->json(
            $this->api()
                ->profileInvestmentsEarnings()
                ->toUrl()
        );
    }
}
