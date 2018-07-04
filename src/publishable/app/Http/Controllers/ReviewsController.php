<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user reviews api endpoints.
 */
class ReviewsController extends Controller
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
     * Get list reviews for authorized user.
     *
     * @return string JSON response list user reviews
     */
    public function index()
    {
        return response()->json(
            $this->api()
                ->profileReviews()
                ->json()
        );
    }
}
