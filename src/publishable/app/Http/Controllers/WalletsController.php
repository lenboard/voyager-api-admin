<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user wallets api endpoints.
 */
class WalletsController extends Controller
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
     * Get list wallets for authorized user.
     *
     * @return string JSON response list user wallets
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileWallets()
                ->json()
        );
    }

    /**
     * Get one wallet by id for authorized user.
     *
     * @param integer $id Investment id
     * @return string JSON response view wallet
     */
    public function show($id)
    {
        return response()->json(
            $this->api()
                ->profileWallet($id)
                ->json()
        );
    }
}
