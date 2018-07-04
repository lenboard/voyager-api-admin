<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use UsesApi;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/api';

    /**
     * Redirect after user logout
     *
     * @var string
     */
    protected $redirectAfterLogout = '/api';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming login request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'login'    => 'required|max:255',
            'email'    => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Display user login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('api/login/form');
    }

    /**
     * Login user.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {

            return redirect()
                ->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where(['email' => $request->input('email')])->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {

            Auth::login($user);
            $this->createNewSecurities();

            return redirect($this->redirectTo);
        }

        $response = $this->api()
                ->login()
                ->post($request->all())
                ->json();

        if ($response->success) {
            $dataUser = $response->data;

            $user = User::create([
                'login'     => $dataUser->login,
                'email'     => $dataUser->email,
                'api_token' => encrypt($dataUser->token),
                'api_id'    => encrypt($dataUser->id),
                'password'  => $request->input('password'),
            ]);

            if (!$user) {
                $validator->errors()->add('email', 'ааЕ баДаАаЛаОбб аЗаАбаЕаГаИбббаИбаОаВаАбб аПаОаЛбаЗаОаВаАбаЕаЛб');

                return redirect()
                    ->route('login')
                    ->withErrors($validator)
                    ->withInput();
            }

            Auth::login($user);

            $this->createNewSecurities();

            return redirect($this->redirectTo);
        }

        $validator->errors()->add('email', $response->error);

        return redirect()
            ->route('login')
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * Logout user.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();

        return redirect($this->redirectAfterLogout);
    }

    /**
     * Create new user security.
     *
     * @return void
     */
    protected function createNewSecurities()
    {
        $this->api()
            ->profileSecurityCreate()
            ->post([
                'ip'      => request()->ip(),
                'browser' => request()->input('user-agent'),
            ]);
    }
}
