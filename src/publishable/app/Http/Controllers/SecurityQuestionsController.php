<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user security questions api endpoints.
 */
class SecurityQuestionsController extends Controller
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
     * Get list security questions for authorized user.
     *
     * @return string JSON response list user security questions
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileSecurityQuestions()
                ->json()
        );
    }

    /**
     * Get one security question by id for authorized user.
     *
     * @param integer $id Security question id
     * @return string JSON response view security question
     */
    public function show($id)
    {
        return response()->json(
            $this->api()
                ->profileSecurityQuestion($id)
                ->json()
        );
    }
}
