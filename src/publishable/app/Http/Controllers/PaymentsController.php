<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;
use App\Support\Classes\Api\Pagination;
use App\ApiPaymentForm;

/**
 * Controller for user payments api endpoints.
 */
class PaymentsController extends Controller
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
     * Display page list payments for authorized user.
     *
     * @param \Illuminate\Http\Request $request Application request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $securityPaginationParam = 'securityPage';
        $securitiesCurrentPage   = $request->get($securityPaginationParam, 1);

        $payments = $this->api()
            ->profilePayments()
            ->with(
                'user',
                'user::user_information',
                "user::securities:order(created_at|asc):pagination({$securitiesCurrentPage},3)"
            )
            ->pagination($request->get('page'), $request->get('perPage'))
            ->get();

        $pagination      = $payments->pagination();
        $firstPayment    = $payments->first();

        $user                = data_get($firstPayment, 'user', null);
        $userInformation     = data_get($firstPayment, 'user.user_information', null);
        $userSecuritiesData  = data_get($firstPayment, 'user.securities', null);
        $userSecurities      = data_get($userSecuritiesData, 'data', []);

        $securitiesPagination = new Pagination($request, data_get($userSecuritiesData, 'pagination'), $securityPaginationParam);

        return view('api.payments.index', compact('payments', 'pagination', 'user', 'userInformation', 'userSecurities', 'securitiesPagination'));
    }

    /**
     *  Display page with api payments systems
     *
     * @return
     */
    public function publicPaymentSystems()
    {
        $paymentSystems = $this->api()
            ->publicPaymentSystems()
            ->get();

        return view('api.payments.payment_systems', compact('paymentSystems'));
    }

    /**
     * Display payment create form
     *
     * @param \Illuminate\Http\Request $request Application request
     * @return \Illuminate\Http\Response
     */
    public function showPaymentForm(Request $request)
    {
        $order = 'ORDER_' . str_random(32);

        $dropDownListInvestmentPlans = collect(
            $this->api()
                ->publicInvestmentPlans()
                ->data()
            )
            ->pluck('name', 'id')
            ->all();

        $dropDownListPaymentSystems = collect(
            $this->api()
                ->publicPaymentSystems()
                ->data()
            )
            ->pluck('name', 'id')
            ->all();

        $dropDownListPaymentTypes = [
            'deposit'  => 'Deposit',
            'withdraw' => 'Withdraw',
        ];

        return view('api.payments.create-form', compact('order', 'dropDownListInvestmentPlans', 'dropDownListPaymentSystems', 'dropDownListPaymentTypes'));
    }

    /**
     * Display page payment detail by id for authorized user.
     *
     * @param integer $id Payment id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $payment = $this->api()
            ->profilePayment($id)
            ->get();

        return view('api.payments.show', compact('payment'));
    }

    /**
     * Ajax get payment form for payment by id
     *
     * @param int $id Payment id
     * @return \Illuminate\Http\Response
     */
    public function getPayForm($id)
    {
        $payment = $this->api()
            ->profilePayment($id)
            ->data();

        $hashListPaymentSystems = collect(
            $this->api()
                ->publicPaymentSystems()
                ->data()
            )
            ->pluck('name', 'id')
            ->all();

        if (!$payment->payment_type) {
            return '';
        }

        $apiPaymentForm = new ApiPaymentForm($hashListPaymentSystems[ $payment->payment_system_id ], $payment->payment_type, $id);

        $paymentForm = $this->api()
            ->getPaymentForm($apiPaymentForm)
            ->json();

        return view('api.payments.payment-form', compact('paymentForm'));
    }

    /**
     * Display page with form update payment.
     *
     * @param int $id Payment id
     * @return \Illuminate\Http\Response
     */
    public function showUpdateForm($id)
    {
        $payment = $this->api()
            ->profilePayment($id)
            ->data();

        $dropDownListInvestmentPlans = collect(
            $this->api()
                ->publicInvestmentPlans()
                ->data()
            )
            ->pluck('name', 'id')
            ->all();

        $dropDownListPaymentSystems = collect(
            $this->api()
                ->publicPaymentSystems()
                ->data()
            )
            ->pluck('name', 'id')
            ->all();

        $dropDownListPaymentTypes = [
            'deposit'  => 'Deposit',
            'withdraw' => 'Withdraw',
        ];

        return view('api.payments.show-update-form', compact('payment', 'dropDownListInvestmentPlans', 'dropDownListPaymentSystems', 'dropDownListPaymentTypes'));
    }

    /**
     * Update user payment.
     *
     * @param \Illuminate\Http\Request $request Application request
     * @param int $id Payment id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = $this->api()
            ->profileUpdatePayment($id)
            ->post($request->only(['amount', 'investment_plan_id', 'payment_system_id', 'payment_type']))
            ->json();

        if ($response->success) {
            return redirect()->route('api.payments.show', ['id' => $id]);
        }

        return redirect()->route('api.payments.show-update-form', ['id' => $id]);
    }

    /**
     * Create payment by id for authorized user.
     *
     * @param \Illuminate\Http\Request $request Application request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = $this->api()
            ->profilePaymentCreate()
            ->post($request->except(['_token']))
            ->json();

        if ($response->success) {
            return redirect()->route('api.payments.index');
        }

        return redirect()->route('api.payments.index');
    }

    /**
     * Update payment by id for authorized user.
     *
     * @param integer $id Payment id
     * @param \Illuminate\Http\Request $request Request
     * @return string JSON response view payment
     */
    /*public function update($id, Request $request)
    {
        return response()->json(
            $this->api()
                ->profilePayment($id)
                ->post($request->all())
                ->json()
        );
    }*/
}
