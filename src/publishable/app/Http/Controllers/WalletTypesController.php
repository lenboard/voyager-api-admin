<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user wallet types api endpoints.
 */
class WalletTypesController extends Controller
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
     * Get list wallet types for authorized user.
     *
     * @return string JSON response list user wallet types
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileWalletTypes()
                ->json()
        );
    }

    /**
     *
     */
    public function publicWalletTypes()
    {
        $walletTypes = $this->api()
                ->publicWalletTypes()
                ->get();

        return view('api.wallet-types.public_wallet_types', compact('walletTypes'));
    }

    /**
     * Get one wallet type by id for authorized user.
     *
     * @param integer $id wallet type id
     * @return string JSON response view wallet type
     */
    public function show($id)
    {
        return response()->json(
            $this->api()
                ->profileWalletType($id)
                ->json()
        );
    }
}
