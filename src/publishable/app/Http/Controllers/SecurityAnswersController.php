<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user security answers api endpoints.
 */
class SecurityAnswersController extends Controller
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
     * Get list security answers for authorized user.
     *
     * @return string JSON response list user security answers
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileSecurityAnswers()
                ->json()
        );
    }

    /**
     * Get one security answer by id for authorized user.
     *
     * @param integer $id Security answer id
     * @return string JSON response view security answer
     */
    public function show($id)
    {
        return response()->json(
            $this->api()
                ->profileSecurityAnswer($id)
                ->json()
        );
    }
}
