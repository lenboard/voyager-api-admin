<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;
use App\Http\Traits\CurrentUser;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/**
 * Controller for user profile api endpoints
 */
class ProfileController extends Controller
{
    use UsesApi, CurrentUser;

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
     * Display page user profile for autorized user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
       $userProfile = $this->api()
            ->profile()
            ->with('securities', 'payments')
            ->calculating('sum|id')
            ->get();

        //dd($userProfile->json());

        return view('api.profile.index', compact('userProfile'));
    }

    /**
     * Display page user profile with relations
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function profileWith(Request $request)
    {
        $securities = 'securities';

        if ($ip = $request->get('security_ip', '')) {
            $securities .= ":where(ip,=,{$ip})";
        }
        $profileRequest = $this->api()
            ->profile()
            ->with(
                $securities,
                'payments:order(created_at|desc)'
            )
            ->get();

        $profile    = $profileRequest->get('data');
        $securities = $profileRequest->get('securities');
        $payments   = $profileRequest->get('payments');

        return view('api.profile.with-securities', compact('profile', 'securities', 'payments'));
    }

    /**
     * Display page with user except information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function profileWithExcept(Request $request)
    {
        $userProfile = $this->api()
            ->profile()
            ->with('payments')
            ->get();

        $userProfileExcept = $userProfile->except(['password', 'token', 'payments.order_id']);

        return view('api.profile.except', compact('userProfile', 'userProfileExcept'));
    }

    /**
     * Display page for update user profile form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showUpdateProfileForm()
    {
        $userProfile = $this->api()
            ->profile()
            ->get();

        return view('api.profile.form', compact('userProfile'));
    }

    /**
     * Display page with method only.
     *
     * @return mixed
     */
    public function profileWithOnly()
    {
        $userProfile = $this->api()
            ->profile()
            ->with('securities')
            ->get();

        $userProfileWithOnly = $userProfile->only(['email', 'password', 'securities.id', 'securities.user_id', 'securities.created_at']);

        return view('api.profile.only', compact('userProfile', 'userProfileWithOnly'));
    }

    /**
     * Update autorized user profile
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $response = $this->api()
            ->profile()
            ->post($request->except(['_token']))
            ->json();

        if ($response->success) {
            $information = $request->except(['login']);

            if (empty(array_get($information, 'password'))) {
                array_forget($information, 'password');
            }

            $this->user()->update($information);

            return redirect()->route('api.profile.index');
        }

        $bag = new MessageBag();

        foreach ($response->error as $nameField => $errors) {
              foreach ($errors as $errorText) {
                  $bag->add($nameField, $nameField . ' ' . $errorText);
              }
        }

        return redirect()->back()->with('errors', session()->get('errors', new ViewErrorBag)->put('default', $bag));
    }
}
