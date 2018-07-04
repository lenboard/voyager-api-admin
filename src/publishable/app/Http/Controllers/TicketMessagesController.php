<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user ticket messages api endpoints.
 */
class TicketMessagesController extends Controller
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
     * Get list ticket messages for authorized user.
     *
     * @return string JSON response list user ticket messages
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileTicketMessages()
                ->json()
        );
    }

    /**
     * Get one ticket message by id for authorized user.
     *
     * @param integer $id Security id
     * @return string JSON response view ticket message
     */
    public function show($id)
    {
        return response()->json(
            $this->api()
                ->profileTicketMessage($id)
                ->json()
        );
    }
}
