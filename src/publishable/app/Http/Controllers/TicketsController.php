<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user tickets api endpoints.
 */
class TicketsController extends Controller
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
     * Get list tickets for authorized user.
     *
     * @return string JSON response list user tickets
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileTickets()
                ->json()
        );
    }

    /**
     * Get one ticket by id for authorized user.
     *
     * @param integer $id Security id
     * @return string JSON response view ticket
     */
    public function show($id)
    {
        return response()->json(
            $this->api()
                ->profileTicket($id)
                ->json()
        );
    }
}
