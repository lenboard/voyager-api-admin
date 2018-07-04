<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;

/**
 * Controller for user information api endpoints.
 */
class UserInformationController extends Controller
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
     * Display page user information for authorized user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userInformation = $this->api()
            ->userInformation()
            ->get();

        return view('api.user-information.index', compact('userInformation'));
    }

    /**
     * Display update user information form for authorized user.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUpdateUserInformationForm()
    {
        $userInformation = $this->api()
            ->userInformation()
            ->get();

        return view('api.user-information.form', compact('userInformation'));
    }

    /**
     * Update user information for authorized user.
     *
     * @param \Illuminate\Http\Request $request Request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $attributes = $request->except('_token');

        $response = $this->api()
            ->userInformationUpdate()
            ->post($attributes)
            ->json();

        if ($response->success) {
            return redirect()->route('api.user.information.index');
        }

        // действия при неудаче обновления данных
        return redirect()->route('api.user.information.index');
    }
}
