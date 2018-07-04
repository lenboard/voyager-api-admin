<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use UsesApi;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/api';

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'login'    => 'required|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Register new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {

            return redirect()
                ->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        $response = $this->api()
            ->register()
            ->post($request->all())
            ->json();

        if ($response->success) {

            $user = User::create([
                'login'     => $response->data->login,
                'email'     => $response->data->email,
                'api_token' => encrypt($response->data->token),
                'api_id'    => encrypt($response->data->id),
                'password'  => $request->input('password'),
            ]);

            return redirect($this->redirectTo);
        }

        foreach ($response->error as $fieldName => $listErrors) {
            foreach ($listErrors as $error) {
                $validator->errors()->add($fieldName, $fieldName . ' ' . $error);
            }
        }

        return redirect()
            ->route('register')
            ->withErrors($validator)
            ->withInput();
    }

    /**
     * Display user register form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('api/register/form');
    }
}
