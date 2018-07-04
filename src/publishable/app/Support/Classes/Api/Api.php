<?php

namespace App\Support\Classes\Api;

use Illuminate\Support\Str;
use GuzzleHttp\Client as Guzzle;
use App\Support\Classes\Api\Captcha;
use App\Support\Classes\Api\Auth\Auth;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Facades\Auth as IlluminateAuth;
use App\Http\Traits\CurrentUser;

class Api
{
    use CurrentUser;

    /**
     * API requests stack.
     *
     * @var array
     */
    public $requests = [];

    /**
     * Guzzle client instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Illuminate request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Encrypter instance.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * Captcha instance.
     *
     * @var \App\Support\Classes\Api\Captcha
     */
    protected $captcha;

    /**
     * Auth instance.
     *
     * @var \App\Support\Classes\Api\Auth\Auth
     */
    protected $auth;

    /**
     * Config repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * String manipulator instance.
     *
     * @var \Illuminate\Support\Str
     */
    protected $str;

    /**
     * @var string Api method
     */
    public $apiMethod = null;

    /**
     * Max number which be add to nonce for one auth request
     *
     * @var int
     */
    const MAX_ADD_NUMBER_FOR_ONE_AUTH_REQUEST = 100;

    /**
     * API constructor.
     *
     * @param  Guzzle  $guzzle
     * @param  IlluminateRequest  $request
     * @param  Encrypter  $encrypter
     * @param  Captcha  $captcha
     * @param  Auth  $auth
     * @param  Repository  $config
     * @param  Str  $str
     * @return void
     */
    public function __construct(
        Guzzle $guzzle,
        IlluminateRequest $request,
        Encrypter $encrypter,
        Captcha $captcha,
        Auth $auth,
        Repository $config,
        Str $str)
    {
        $this->guzzle = $guzzle;
        $this->request = $request;
        $this->encrypter = $encrypter;
        $this->captcha = $captcha;
        $this->auth = $auth->setClient($this);
        $this->config = $config;
        $this->str = $str;
    }

    /**
     * Create custom request.
     *
     * @param  string $endpoint
     * @param  bool   $authenticate
     * @return \App\Support\Classes\Api\Request
     */
    public function request($endpoint, $authenticate = false)
    {
        return $this->createRequest('/'.ltrim($endpoint, '/'), $authenticate);
    }

    /**
     * Register new user with new api.
     *
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function register($version = 'api_v1', $type = 'json')
    {
        $this->apiMethod = __FUNCTION__;
        return $this->createRequest('/' . $version . '/register.' . $type, /* authenticate */ false);
    }

    /**
     * Login user with new api.
     *
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function login($version = 'api_v1', $type = 'json')
    {
        $this->apiMethod = __FUNCTION__;
        return $this->createRequest('/' . $version . '/login.' . $type, /* authenticate */ false);
    }

    /**
     * Get user profile.
     *
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function profile($version = 'api_v1', $type = 'json')
    {
        $this->apiMethod = __FUNCTION__;

        return $this->createRequest('/' . $version . '/profile/' . $this->user()->getApiId() . '.' . $type, /* authenticate */ true);
    }

    // ================================================= User securities

    /**
     * Get user securities.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurities($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/securities", /* authenticate */ true);
    }

    /**
     * Get user security by id.
     *
     * @param $id User security id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurity($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/securities/{$id}", /* authenticate */ true);
    }

    /**
     * Create user securities.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityCreate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/securities/create", /* authenticate */ true);
    }

    // ================================================= End user securities
    // ================================================= User payments

    /**
     * Get user payments.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profilePayments($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/payments", /* authenticate */ true);
    }

    /**
     * Request to api endpoint user payments by payment id.
     *
     * @param $id User payment id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profilePayment($id, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId          = $this->user()->getApiId();

        return $this->createRequest("/{$version}/profile/{$userId}/payments/{$id}", /* authenticate */ true);
    }

    /**
     * Create user payment.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profilePaymentCreate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/payments/create", /* authenticate */ true);
    }

    /**
     * Request to api endpoint update user payment.
     *
     * @param $id User payment id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileUpdatePayment($id, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId          = $this->user()->getApiId();

        return $this->createRequest("/{$version}/profile/{$userId}/payments/{$id}", /* authenticate */ true);
    }

    /**
     * Public endpoint for get list payment systems
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function publicPaymentSystems($version = 'api_v1')
    {
        return $this->createRequest("/{$version}/public/payment_systems", /* authenticate */ false);
    }

    /**
     * Endpoint for get payment form for some payment
     *
     * @param $id User payment id
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function getPaymentForm($id, $version = 'api_v1', $type = 'json')
    {
        $userId = $this->user()->getApiId();

        return $this->createRequest("/{$version}/profile/{$userId}/payment_form/{$id}.{$type}", /* authenticate */ true);
    }

    // ================================================= End user payments
    // ================================================= User investments

    /**
     * Get user investments.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestments($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/investments", /* authenticate */ true);
    }

    /**
     * Get user investment by id.
     *
     * @param $id User investment id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestment($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/investment/{$id}", /* authenticate */ true);
    }

    /**
     * Get user investment earnings.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentsEarnings($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/investment/earnings", /* authenticate */ true);
    }

    // ================================================= End user investments
    // ================================================= User investments earnings

    /**
     * Get user investments earnings.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentEarningsList($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/investment/earnings", /* authenticate */ true);
    }

    /**
     * Get user investment earning by id.
     *
     * @param $id User investment earning id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentEarning($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/investment_earnings/{$id}", /* authenticate */ true);
    }

    /**
     * Get user investments earnings earnings.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentEarnings($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/investment_earnings/earnings", /* authenticate */ true);
    }

    // ================================================= End user investments earnings
    // ================================================= User investment plans

    /**
     * Get user investment plans.
     *
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentPlans($version = 'api_v1', $type = 'json')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_investment_plans.{$type}", /* authenticate */ true);
    }

    /**
     * Get user investment plan by id.
     *
     * @param $id User investment plan id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentPlan($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_investment_plans/{$id}", /* authenticate */ true);
    }

    /**
     * Get user available for invest investment plans.
     *
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestAvailableInvestmentPlans($version = 'api_v1', $type = 'json')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_invest_available_investment_plans.{$type}", /* authenticate */ true);
    }

    /**
     * Get user investment plans requests.
     *
     * @param string $version Version api
     * @param string $type Type response
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentPlansRequests($use_agent_version = false, $version = 'api_v1', $type = 'json')
    {
        $userId = $this->user()->getApiId();
        $route_version = $use_agent_version ? '_agent' : '';
        return $this->createRequest("/{$version}/profile/{$userId}/request_user_investment_plans{$route_version}.{$type}", /* authenticate */ true);
    }

    /**
     * Create user investment plans requests.
     *
     * @param bool $use_agent_version Use agent version of route
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentPlansRequestCreate($use_agent_version = false, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        $route_version = $use_agent_version ? '_agent' : '';
        return $this->createRequest("/{$version}/profile/{$userId}/request_user_investment_plan{$route_version}", /* authenticate */ true);
    }

    /**
     * Update user investment plans requests.
     *
     * @param $id ReqeustUserInvestmentPlan id
     * @param bool $use_agent_version Use agent version of route
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileInvestmentPlansRequestUpdate($id, $use_agent_version = false, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        $route_version = $use_agent_version ? '_agent' : '';
        return $this->createRequest("/{$version}/profile/{$userId}/request_user_investment_plan{$route_version}/{$id}", /* authenticate */ true);
    }

    /**
     * Public endpoint for get list investment plans
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function publicInvestmentPlans($version = 'api_v1')
    {
        return $this->createRequest("/{$version}/public/investment_plans", /* authenticate */ false);
    }

    // ================================================= End user investment plans
    // ================================================= User tickets

    /**
     * Get user tickets.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileTickets($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/tickets", /* authenticate */ true);
    }

    /**
     * Create user ticket.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileTicketCreate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/tickets/create", /* authenticate */ true);
    }

    /**
     * Get user ticket by id.
     *
     * @param $id User ticket id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileTicket($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/tickets/{$id}", /* authenticate */ true);
    }

    // ================================================= End user tickets
    // ================================================= User ticket message

    /**
     * Get user ticket messages.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileTicketMessages($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/ticket_messages", /* authenticate */ true);
    }

    /**
     * Get user ticket message by id.
     *
     * @param $id User ticket message id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileTicketMessage($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/ticket_messages/{$id}", /* authenticate */ true);
    }

    // ================================================= End user ticket message
    // ================================================= User referrals

    /**
     * Get user referrals.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileReferrals($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/referrals", /* authenticate */ true);
    }

    /**
     * Get user referral by id.
     *
     * @param $id User referral id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileReferral($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/referrals/{$id}", /* authenticate */ true);
    }

    /**
     * Register new team member
     *
     * @param string $version
     * @return Request
     */
    public function profileRegisterReferral($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/register_referral", /* authenticate */ true);
    }

    // ================================================= End user referrals
    // ================================================= Hit referral link
    /**
     * Public endpoint for hit referral link
     *
     * @param string $version
     * @param string $type
     * @return Request
     */
    public function publicReferralLinkHit($referrer_id, $version = 'api_v1', $type = 'json')
    {
        return $this->createRequest("/{$version}/public/referral_link_hit/{$referrer_id}", /* authenticate */ false);
    }
    // ================================================= End statistics
    // ================================================= User referral earnings

    /**
     * Get user referral earnings list.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileReferralEarningsList($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/referral_earnings", /* authenticate */ true);
    }

    /**
     * Get user referral earnings by id.
     *
     * @param $id User referral earning id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileReferralEarning($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/referral_earnings/{$id}", /* authenticate */ true);
    }

    /**
     * Get user referral earnings.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileReferralEarnings($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/referral_earnings/earnings", /* authenticate */ true);
    }

    // ================================================= End user referral earnings
    // ================================================= User reviews

    /**
     * Get user reviews.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileReviews($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_reviews", /* authenticate */ true);
    }

    // ================================================= End user referral earnings
    // ================================================= User security answers

    /**
     * Get user security answers.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityAnswers($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_answers", /* authenticate */ true);
    }

    /**
     * Create user security answer.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityAnswerCreate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_answers/create", /* authenticate */ true);
    }

    /**
     * Update user security answer.
     *
     * @param $id User security question id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityAnswerUpdate($id, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId          = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_answers/{$id}", /* authenticate */ true);
    }

    // ================================================= End user security answers
    // ================================================= User security questions

    /**
     * Get user security questions.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityQuestions($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_questions", /* authenticate */ true);
    }

    /**
     * Get user security question by id.
     *
     * @param $id User security question id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityQuestion($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_questions/{$id}", /* authenticate */ true);
    }

    /**
     * Create user security question.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityQuestionCreate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_questions/create", /* authenticate */ true);
    }

    /**
     * Update user  security question.
     *
     * @param $id User security question id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileSecurityQuestionUpdate($id, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId          = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_security_questions/{$id}", /* authenticate */ true);
    }

    // ================================================= End user security questions
    // ================================================= Public security questions
    /**
     * Public endpoint for get security questions
     *
     * @param string $version Version api
     * @param string $type Type response format
     * @return \App\Support\Classes\Api\Request
     */
    public function publicSecurityQuestions($version = 'api_v1', $type = 'json')
    {
        return $this->createRequest("/{$version}/public/security_questions", /* authenticate */ false);
    }
    // ================================================= End Public security questions
    // ================================================= User wallets

    /**
     * Get user wallets.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileWallets($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/wallets", /* authenticate */ true);
    }

    /**
     * Create/Update user wallet.
     *
     * @param $id User wallet id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileWalletCreateUpdate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/wallet", /* authenticate */ true);
    }

    // ================================================= End user wallets
    // ================================================= User wallet types

    /**
     * Get user wallet types.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileWalletTypes($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/wallet_types", /* authenticate */ true);
    }

    /**
     * Get user wallet type by id.
     *
     * @param $id User wallet type id
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function profileWalletType($id, $version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/wallet_types/{$id}", /* authenticate */ true);
    }

    /**
     * Public endpoint for get list wallet types
     *
     * @param string $version Version api
     * @param string $type Type response format
     * @return \App\Support\Classes\Api\Request
     */
    public function publicWalletTypes($version = 'api_v1', $type = 'json')
    {
        return $this->createRequest("/{$version}/public/wallet_types", /* authenticate */ false);
    }

    // ================================================= End user wallet types
    // ================================================= User information

    /**
     * Get user information.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function userInformation($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_information", /* authenticate */ true);
    }

    /**
     * Update user information.
     *
     * @param string $version Version api
     * @return \App\Support\Classes\Api\Request
     */
    public function userInformationUpdate($version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/user_information", /* authenticate */ true);
    }

    // ================================================= End user information
    // ================================================= User register requests
    /**
     * Get registration requests list
     *
     * @param string $version
     * @return Request
     */
    public function profileRegisterRequests($version = 'api_v1')
    {
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/registration_requests", /* authenticate */ true);
    }
    /**
     * Post registration request
     *
     * @param string $version
     * @return Request
     */
    public function profileRegisterRequestUpdate($id, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/registration_request/{$id}", /* authenticate */ true);
    }
    /**
     * Post registration request accept
     *
     * @param string $version
     * @return Request
     */
    public function profileRegisterRequestAccept($id, $version = 'api_v1')
    {
        $this->apiMethod = __FUNCTION__;
        $userId = $this->user()->getApiId();
        return $this->createRequest("/{$version}/profile/{$userId}/registration_request_accept/{$id}", /* authenticate */ true);
    }
    // ================================================= End register requests
    // ================================================= Statistics
    /**
     * Public endpoint for get statistics
     *
     * @param string $version Version api
     * @param string $type Type response format
     * @return \App\Support\Classes\Api\Request
     */
    public function publicStatistics($version = 'api_v1', $type = 'json')
    {
        $this->apiMethod = __FUNCTION__;
        return $this->createRequest("/{$version}/public/statistics", /* authenticate */ false);
    }
    // ================================================= End statistics
    // ================================================= Online
    /**
     * Public endpoint for get agents online
     * @param $count
     * @param string $version Version api
     * @param string $type Type response format
     * @return \App\Support\Classes\Api\Request
     */
    public function publicAgentsOnline($count, $version = 'api_v1', $type = 'json')
    {
        return $this->createRequest("/{$version}/public/agents_online/{$count}", /* authenticate */ false);
    }
    /**
     * Public endpoint for get users online
     * @param $count
     * @param string $version Version api
     * @param string $type Type response format
     * @return \App\Support\Classes\Api\Request
     */
    public function publicUsersOnline($login_list, $version = 'api_v1', $type = 'json')
    {
        $login_list = json_encode($login_list);
        return $this->createRequest("/{$version}/public/users_online/{$login_list}", /* authenticate */ false);
    }
    // ================================================= End online


    /**
     * Get API response or call respective method.
     *
     * @param  string  $name
     * @return \App\Support\Classes\Api\Request|null
     */
    public function __get($name)
    {
        $callable = [$this, $name];

        if (is_callable($callable)) {
            $this->requests[$name] = call_user_func($callable);

            return $this->requests[$name];
        }

        return null;
    }

    /**
     * Get API auth container.
     *
     * @return \App\Support\Classes\Api\Auth\Auth
     */
    public function auth()
    {
        return $this->auth;
    }

    /**
     * Get API encrypter.
     *
     * @return \Illuminate\Encryption\Encrypter
     */
    public function encrypter()
    {
        return $this->encrypter;
    }

    /**
     * Get API captcha.
     *
     * @return \App\Support\Classes\Api\Captcha
     */
    public function captcha()
    {
        return $this->captcha;
    }

    /**
     * Create new API request.
     *
     * @param  string  $endpoint
     * @param  bool  $requireAuth
     * @return \App\Support\Classes\Api\Request
     */
    protected function createRequest($endpoint, $requireAuth = false)
    {
        $request = new Request(
            $endpoint,
            false,
            $this->auth,
            $this->guzzle,
            $this->request,
            $this->config,
            $this->str,
            $this->apiMethod
        );

        $this->apiMethod = null;

        if ($requireAuth) {
            $request->addQueryParameters($this->addToRequestUserToken());
        }

        $this->requests[$endpoint] = $request;

        return $this->requests[$endpoint];
    }

    /**
     * Generate user sign and nonce for request
     *
     * @return array
     */
    protected function addToRequestUserToken()
    {
        $user  = IlluminateAuth::user();

        if (!$user) {
            echo json_encode([
                'error' => 'You must be authorized.',
            ]);
            exit;
        }

        $time  = round(microtime(true) * 1000);

        if (session()->has('maxAuthRequestPerSecond')) {
            $max = session()->get('maxAuthRequestPerSecond');

            if ($max < self::MAX_ADD_NUMBER_FOR_ONE_AUTH_REQUEST) {
                $max++;
            } else {
                $max = 1;
            }
        } else {
            $max = 1;
        }

        session()->put('maxAuthRequestPerSecond', $max);

        $time .= str_pad($max, strlen(self::MAX_ADD_NUMBER_FOR_ONE_AUTH_REQUEST), 0, STR_PAD_LEFT);

        $token = hash('sha256', $time . decrypt($user->api_token));

        $data = [
            'nonce' => $time,
            'sign'  => $token,
        ];

        return $data;
    }
}
