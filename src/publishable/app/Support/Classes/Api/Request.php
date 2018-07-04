<?php

namespace App\Support\Classes\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Http\UploadedFile;
use App\Support\Classes\Api\Auth\Auth;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request as IlluminateRequest;
use App\Support\Classes\Api\Exception\ApiException;
use App\Support\Classes\Api\Exception\AuthException;
use App\Support\Classes\Api\Exception\NotFoundException;
use App\Support\Classes\Api\Exception\HttpErrorException;
use App\Support\Classes\Api\Exception\ValidationException;
use App\Support\Classes\Api\Exception\RateLimitExceededException;
use App\helpers\Helper;

class Request
{
    /**
     * GuzzleHttp instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * API response instance.
     *
     * @var \App\Support\Classes\Api\Response
     */
    protected $response;

    /**
     * Illuminate request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Request endpoint.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Config repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Request parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Authentication provider.
     *
     * @var \App\Support\Classes\Api\Auth\Auth
     */
    protected $auth = null;

    /**
     * Perform authenticated request.
     *
     * @var bool
     */
    protected $withAuth;

    /**
     * String manipulator instance.
     *
     * @var \Illuminate\Support\Str
     */
    protected $str;

    /**
     * Max depth for multipart array conversion.
     *
     * @var integer
     */
    protected $maxMultipartDepth = 5;

    /**
     * @var array Matching api methods with entity
     */
    private $relationEndpointsWithEntity = [
        'register'                              => 'user',
        'login'                                 => 'user',
        'profile'                               => 'user',
        'profileRegisterReferral'               => 'user',
        'profileSecurityCreate'                 => 'security',
        'userInformationUpdate'                 => 'user_information',
        'profilePayment'                        => 'payment',
        'profilePaymentCreate'                  => 'payment',
        'profileUpdatePayment'                  => 'payment',
        'profileTicketCreate'                   => 'ticket',
        'profileWalletCreateUpdate'             => 'wallet',
        'profileRegisterRequestUpdate'          => 'registration_request',
        'profileRegisterRequestAccept'          => 'registration_request',
        'profileInvestmentPlansRequestCreate'   => 'request_user_investment_plan',
        'profileInvestmentPlansRequestUpdate'   => 'request_user_investment_plan',
        'profileSecurityQuestionCreate'         => 'user_security_question',
        'profileSecurityQuestionUpdate'         => 'user_security_question',
        'profileSecurityAnswerCreate'           => 'user_security_answer',
        'profileSecurityAnswerUpdate'           => 'user_security_answer',
    ];

    /**
     * @var string Api method
     */
    private static $apiMethod = null;

    /**
     * Construct API request.
     *
     * @param  string  $endpoint
     * @param  bool  $withAuth
     * @param  Auth  $auth
     * @param  Guzzle  $guzzle
     * @param  IlluminateRequest  $request
     * @param  Repository  $config
     * @param  Str  $str
     * @return void
     */
    public function __construct(
        $endpoint,
        $withAuth = false,
        Auth $auth,
        Guzzle $guzzle,
        IlluminateRequest $request,
        Repository $config,
        Str $str,
        $apiMethod = null)
    {
        $this->config = $config;
        $this->guzzle = $guzzle;
        $this->request = $request;
        $this->endpoint = '/' . trim($endpoint, '/');
        $this->str = $str;
        $this->withAuth = $withAuth;
        $this->auth = $auth;
        self::$apiMethod = $apiMethod;
    }

    /**
     * Perform authenticated request.
     *
     * @return static
     */
    public function withAuth()
    {
        $this->withAuth = true;
        return $this;
    }

    /**
     * Convert request to Url.
     *
     * @return string
     */
    public function toUrl()
    {
        $baseUri = rtrim($this->config->get('api.uri'), '/');

        return $baseUri . $this->endpoint . ($this->hasQuery() ? '?' : '') . $this->buildQuery();
    }

    /**
     * Performs POST request to the specific endpoint.
     *
     * @return \App\Support\Classes\Api\Response
     */
    public function post(array $parameters = [])
    {
        if (self::$apiMethod && isset($this->relationEndpointsWithEntity[ self::$apiMethod ])) {
            $entity = $this->relationEndpointsWithEntity[ self::$apiMethod ];
            $parameters = [
                $entity => $parameters,
            ];
        }

        $this->parameters['form_data'] = $parameters;

        $this->response = $this->request('POST');
        return $this->response;
    }

    /**
     * Performs GET request to the specific endpoint.
     *
     * @return \App\Support\Classes\Api\Response
     */
    public function get()
    {
        $this->response = $this->request('GET');
        return $this->response;
    }

    /**
     * Performs DELETE request to the specific endpoint.
     *
     * @return \App\Support\Classes\Api\Response
     */
    public function delete()
    {
        $this->response = $this->request('DELETE');
        return $this->response;
    }

    /**
     * Execute request and retrive response json.
     *
     * @return object
     */
    public function json()
    {
        if (isset($this->response)) {
            return $this->response->json();
        }

        return $this->get()->json();
    }

    /**
     * Execute request and retrive response data.
     *
     * @return object
     */
    public function data()
    {
        if (isset($this->response)) {
            return $this->response->data();
        }

        return $this->get()->data();
    }

    /**
     * Execute request and retrive response meta.
     *
     * @return object
     */
    public function meta()
    {
        if (isset($this->response)) {
            return $this->response->meta();
        }

        return $this->get()->meta();
    }

    /**
     * Execute request and retrive response count.
     *
     * @param  string|null  $group
     * @return integer|null
     */
    public function count($group = null)
    {
        // we'll need a clone to preserve function name as count
        $new = clone $this;
        $new->parameters['query']['count'] = ($group === null ? 'id' : $group);

        return (isset($new->data()->count) ? $new->data()->count : $new->data());
    }

    /**
     * Execute request and retrive response sum.
     *
     * @param  string  $group
     * @return integer|null
     */
    public function sum($group)
    {
        // we'll need a clone to preserve function name as sum
        $new = clone $this;
        $new->parameters['query']['sum'] = $group;

        return (isset($new->data()->sum) ? $new->data()->sum : $new->data());
    }

    /**
     * Execute request and retrive only first data item.
     *
     * @return object
     */
    public function first()
    {
        if (isset($this->response)) {
            return $this->response->first();
        }

        return $this->limit(1)->get()->first();
    }

    /**
     * Search record by parameters.
     *
     * @param  array  $parameters
     * @param  string $condition
     * @param  bool  $exact
     * @return \App\Support\Classes\Api\Request
     */
    public function search(array $parameters, $condition = 'AND', $exact = false)
    {
        if (empty($parameters)) {
            return $this;
        }

        // build key-value pairs
        array_walk($parameters, function (&$item, $key) use ($exact) {
            $item = ($exact ? $key.':'.$item : $key.':'.'*'.$item.'*');
        });

        array_unshift($parameters, $condition);

        $searchString = implode(',', $parameters);

        return $this->q($searchString);
    }

    /**
     * Single method for sorting by column and direction.
     *
     * @param  string  $order
     * @param  string  $sort
     * @return \App\Support\Classes\Api\Request
     */
    public function orderBy($order, $sort)
    {
        return $this
            ->order($order)
            ->sort($sort);
    }

    /**
     * Shortcut for today as selection interval. May not work on some endpoints.
     *
     * @return \App\Support\Classes\Api\Request
     */
    public function today()
    {
        return $this->from(Carbon::today()->toDateString())
            ->to(Carbon::today()->addDay()->toDateString());
    }

    /**
     * Shortcut for yesterday as selection interval. May not work on some endpoints.
     *
     * @return \App\Support\Classes\Api\Request
     */
    public function yesterday()
    {
        return $this->from(Carbon::yesterday()->toDateString())
            ->to(Carbon::yesterday()->addDay()->toDateString());
    }

    /**
     * Shortcut for this week as selection interval. May not work on some endpoints.
     *
     * @return \App\Support\Classes\Api\Request
     */
    public function thisWeek()
    {
        return $this->from(Carbon::now()->startOfWeek()->toDateString())
            ->to(Carbon::now()->endOfWeek()->toDateString());
    }

    /**
     * Send headers.
     *
     * @param  array|null  $headers
     * @return \App\Support\Classes\Api\Request
     */
    public function headers(array $headers = [])
    {
        if (!is_array($headers)) {
            return $this;
        }

        foreach ($headers as $key => $value) {
            if (is_null($value)) {
                unset($headers[$key]);
            }
        }

        if (empty($headers)) {
            return $this;
        }

        $new = clone $this;
        $new->parameters['headers'] = $headers;

        return $new;
    }

    /**
     * Magic method to build query.
     *
     * @param  string  $name
     * @param  array  $arguments
     * @return \App\Support\Classes\Api\Request
     */
    public function __call($name, array $arguments)
    {
        $new = clone $this;

        // convert param name to snake case
        $name = $this->str->snake($name);

        // remove null values from arguments
        $arguments = array_filter($arguments, function($item) {
            return ($item != null);
        });

        // convert bool to integer
        array_walk($arguments, function(&$item) {
            $item = (is_bool($item) ? (int)$item : $item);
        });

        if (!$new->hasQuery()) {
            $new->parameters['query'] = [];
        }

        if ($name === 'with') {
            $arguments = Helper::modifyWithArguments($arguments);

            $new->parameters[ 'query' ][ 'with' ] = $arguments;
        }

        if (empty($arguments) && $name !== 'sort') {
            return $new;
        }

        switch ($name) {
            case 'pagination':
                $new->parameters[ 'query' ][ 'pagination' ] = $this->buildPagination($arguments);
                break;
            case 'order':
            case 'sort':
                if (!isset($new->parameters[ 'query' ][ 'order' ])) {
                    $new->parameters[ 'query' ][ 'order' ] = [];
                }
                $new->parameters[ 'query' ][ 'order' ] = $this->buildOrder($name, $arguments, $new->parameters[ 'query' ][ 'order' ]);
                break;
            case 'limit':
                $perPage = isset($arguments[ 0 ]) ? $arguments[ 0 ] : 30;

                $new->parameters[ 'query' ][ 'pagination' ] = $this->buildPagination([1, $perPage]);
                break;
            case 'where':
            case 'and_where':
            case 'or_where':

                $relationConditions = [];

                foreach ($arguments as $key => $argument) {
                    if (!isset($argument[ 0 ])) {
                        continue;
                    }

                    if (strpos($argument[ 0 ], '.')) {
                        $relationConditions[] = $argument;

                        unset($arguments[ $key ]);
                    }
                }

                if ($relationConditions) {
                    $this->buildConditionForRelations($new->parameters[ 'query' ][ 'with' ], $relationConditions);
                }

                if (!isset($new->parameters[ 'query' ][ 'where' ])) {
                    $new->parameters[ 'query' ][ 'where' ] = [];
                }

                if ($name === 'and_where' && $new->parameters[ 'query' ][ 'where' ]) {
                    $new->parameters[ 'query' ][ 'where' ][] = $this->addConditionJoiner('and');
                } else if ($name === 'or_where' && $new->parameters[ 'query' ][ 'where' ]) {
                    $new->parameters[ 'query' ][ 'where' ][] = $this->addConditionJoiner('or');
                }

                $new->parameters[ 'query' ][ 'where' ][] = $this->buildCondition($arguments);
                break;
            case 'join':
                $new->parameters[ 'query' ][ 'join' ] = $this->buildJoin($arguments);
                break;
            case 'calculating':
                $new->parameters[ 'query' ][ 'calculating' ] = $this->buildCalculating($arguments);
                break;
            case 'start_date':
                $new->parameters[ 'query' ][ 'start_date' ] = array_pop($arguments);
                break;
            case 'end_date':
                $new->parameters[ 'query' ][ 'end_date' ] = array_pop($arguments);
                break;

        }

        return $new;
    }

    /**
     * Send request.
     *
     * @param  string $method
     * @return \App\Support\Classes\Api\Response $response
     */
    protected function request($method)
    {
        $endpoint = $this->endpoint;

        // resolve current paginator page
        if (Pagination::resolveCurrentPage($this->request)) {
            $this->parameters['query']['page'] = isset($this->parameters['query']['page']) ?
                $this->parameters['query']['page'] : Pagination::resolveCurrentPage($this->request);
        }

        // resolve current paginator limit
        if (Pagination::resolveCurrentLimit($this->request)) {
            $this->parameters['query']['limit'] =
                Pagination::resolveCurrentLimit($this->request);
        }

        // check if request has query and build it
        if ($this->hasQuery()) {
            $endpoint .= '?' . $this->buildQuery();
        }

        $response = $this->guzzle->request($method, $endpoint, $this->getConfig());

        if (!$response) {
            throw new AuthException(
                null,
                'Unable to connect to API server. Please check API uri in config/api.php'
            );
        }

        return $this->handleResponse($response);
    }

    /**
     * Handle server response.
     *
     * @param  \App\Support\Classes\Api\Response $response
     * @return \App\Support\Classes\Api\Response
     */
    protected function handleResponse(Response $response)
    {
        switch ($response->getStatusCode()) {
            case 200:
                // everything okay
                return $response;
            case 401:
            case 403:
                throw new AuthException(
                    'Unable to authenticate request with API server.'
                );
            case 404:
                $message = $response->has('message') ?
                    $response->json()->message : sprintf('Requested endpoint "%s" was not found.', $this->endpoint);

                throw new NotFoundException($message);
            case 422:
                $errors = $response->has('errors') ?
                    (array)$response->json()->errors : ['error' => 'Validation error.'];

                throw new ValidationException($errors);
            case 429:
                $status_code = $response->has('status_code') ?
                    $response->json()->status_code : 500;
                $message = $response->has('message') ?
                    $response->json()->message : 'Rate limit exceeded.';

                throw new RateLimitExceededException($status_code, $message);
            default:
                if ($response->isEmpty()) {
                    throw new HttpErrorException(
                        'Unable to connect to API server. Please check API uri in config/api.php.'
                    );
                }

                echo json_encode($response->json());
                exit;

                throw new ApiException(
                    $response->json()->message,
                    $response->json()->status_code
                );
        }
    }

    /**
     * Get GuzzleHttp config.
     *
     * @return array
     */
    protected function getConfig()
    {
        $config = [];

        if ($this->hasFormData()) {
            $this->mergeFormData($config);
        }

        $config['headers'] = [];
        $this->mergeForwaredForHeader($config['headers']);
        $this->mergeAuthHeader($config['headers']);

        if ($this->hasHeaders()) {
            $this->mergeHeaders($config['headers']);
        }

        return $config;
    }

    /**
     * Merge X-Forwarded-For header.
     *
     * @param  array  $headers
     * @return void
     */
    protected function mergeForwaredForHeader(array &$headers)
    {
        $headers = array_merge($headers, [
            'X-Forwarded-For' => app('request')->getClientIp(),
        ]);
    }

    /**
     * Merge Authorization header.
     *
     * @param  array  $headers
     * @return void
     */
    protected function mergeAuthHeader(array &$headers)
    {
        if ($this->withAuth) {
            $headers = array_merge($headers, $this->auth->header());
        }
    }

    /**
     * Merge request headers.
     *
     * @param  array  $headers
     * @return void
     */
    protected function mergeHeaders(array &$headers)
    {
        $headers = array_merge($headers, $this->parameters['headers']);
    }

    /**
     * Check if request has headers.
     *
     * @return bool
     */
    public function hasHeaders()
    {
        if (isset($this->parameters['headers']) && !empty($this->parameters['headers'])) {
            return true;
        }

        return false;
    }

    /**
     * Check if request has query string.
     *
     * @return bool
     */
    protected function hasQuery()
    {
        if (isset($this->parameters['query']) && !empty($this->parameters['query'])) {
            return true;
        }

        return false;
    }

    /**
     * Build query string.
     *
     * @param  array  $parameters
     * @return string
     */
    protected function buildQuery(array $parameters = [])
    {
        if (!$this->hasQuery()) {
            return '';
        }

        if (
            isset($this->parameters[ 'query' ][ 'where' ]) &&
            !empty($this->parameters[ 'query' ][ 'where' ])
        ) {
            if (self::$apiMethod === 'publicStatistics') {
                $this->parameters[ 'query' ][ 'where' ] = $this->buildWhereForStatistics();
            } else {
                $this->parameters[ 'query' ][ 'where' ] = json_encode($this->parameters[ 'query' ][ 'where']);
            }
        }
        if (
            isset($this->parameters[ 'query' ][ 'order' ]) &&
            !empty($this->parameters[ 'query' ][ 'order' ])
        ) {
            if (self::$apiMethod === 'publicStatistics') {
                $this->parameters[ 'query' ][ 'order' ] = $this->buildOrderForStatistics();
            } else {
                $this->parameters[ 'query' ][ 'order' ] = json_encode($this->parameters[ 'query' ][ 'order']);
            }
        }
        if (self::$apiMethod === 'publicStatistics') {
            self::$apiMethod = null;
        }
        if (
            isset($this->parameters[ 'query' ][ 'with' ]) &&
            !empty($this->parameters[ 'query' ][ 'with' ])
        ) {
            $this->parameters[ 'query' ][ 'with' ] = json_encode($this->parameters[ 'query' ][ 'with']);
        }
        if (
            isset($this->parameters[ 'query' ][ 'join' ]) &&
            !empty($this->parameters[ 'query' ][ 'join' ])
        ) {
            $this->parameters[ 'query' ][ 'join' ] = json_encode($this->parameters[ 'query' ][ 'join']);
        }
        if (
            isset($this->parameters[ 'query' ][ 'calculating' ]) &&
            !empty($this->parameters[ 'query' ][ 'calculating' ])
        ) {
            $this->parameters[ 'query' ][ 'calculating' ] = json_encode($this->parameters[ 'query' ][ 'calculating']);
        }

        return http_build_query(!empty($parameters) ? $parameters : $this->parameters['query']);
    }

    /**
     * Check if request has form data.
     *
     * @return bool
     */
    protected function hasFormData()
    {
        if (isset($this->parameters['form_data']) && !empty($this->parameters['form_data'])) {
            return true;
        }

        return false;
    }

    /**
     * Merge multipart or form data into guzzle config.
     *
     * @param  array  $config
     * @param  array  $parameters
     * @return array
     */
    protected function mergeFormData(array &$config, array $parameters = [])
    {
        $parameters = array_wrap(!empty($parameters) ?
            $parameters : $this->parameters['form_data']);

        if ($this->hasMultipartData($parameters)) {
            $config['multipart'] = $this->buildMultipartData($parameters);

            return $config;
        }

        $config['form_params'] = $parameters;
        return $config;
    }

    /**
     * Check if request has multipart data.
     *
     * @param  array  $parameters
     * @return bool
     */
    protected function hasMultipartData(array $parameters)
    {
        if (isset($this->relationEndpointsWithEntity[ self::$apiMethod ])) {
            $parameters = $parameters[ $this->relationEndpointsWithEntity[ self::$apiMethod ] ];
        }

        foreach ($parameters as $value) {
            if ($value instanceof UploadedFile) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build multipart data.
     *
     * @param  array  $parameters
     * @return array
     */
    protected function buildMultipartData(array $parameters)
    {
        if (isset($this->relationEndpointsWithEntity[ self::$apiMethod ])) {
            $parameters = $parameters[ $this->relationEndpointsWithEntity[ self::$apiMethod ] ];
        }

        $func = function ($parameters, $baseName = null, $depth = 0) use (&$func) {
            $data = [];

            if ($depth > $this->maxMultipartDepth) {
                throw new RuntimeException(
                    "Max multidimensional array depth reached. (depth > $depth)"
                );
            }

            foreach ($parameters as $name => &$value) {
                $name = $baseName === null ?
                    $name : ($baseName . '[' . $name . ']');

                switch (true) {
                    case $value instanceof UploadedFile:
                        // file
                        $data[] = [
                            'name'      => $name,
                            'filename'  => $value->getClientOriginalName(),
                            'contents'  => fopen($value->getPathname(), 'r'),
                            'headers'   => [
                                'Content-Type' => $value->getClientMimeType(),
                            ],
                        ];
                        break;
                    case is_array($value):
                        // array
                        $data = array_merge(
                            $func($value, $name, $depth++),
                            $data
                        );
                        break;
                    default:
                        // text param
                        $data[] = [
                            'name'     => $name,
                            'contents' => $value,
                        ];
                        break;
                }
            }

            return $data;
        };

        $baseName = null;
        if (isset($this->relationEndpointsWithEntity[ self::$apiMethod ])) {
            $baseName = $this->relationEndpointsWithEntity[ self::$apiMethod ];
        }

        return $func($parameters, $baseName);
    }

    /**
     * Add query parameters for request.
     *
     * @param array $queryParameters Parameters for query
     * @return void
     */
    public function addQueryParameters($queryParameters = [])
    {
        if (!isset($this->parameters[ 'query' ])) {
            $this->parameters[ 'query' ] = [];
        }
        $this->parameters[ 'query' ] = array_merge($this->parameters[ 'query' ], $queryParameters);
    }

    /**
     * Add pagination to request.
     *
     * @param array $arguments Page and per page parameters
     * @return array JSON encode pagination parameters
     */
    protected function buildPagination($arguments)
    {
        $page    = isset($arguments[ 0 ]) ? $arguments[ 0 ] : 1;
        $perPage = isset($arguments[ 1 ]) ? $arguments[ 1 ] : 30;

        return [
            'page'     => $page,
            'per_page' => $perPage,
        ];
    }

    /**
     * Build order parameters for request.
     *
     * @param string $name (sort|order) Name part order
     * @param array $arguments Arguments for order query
     * @param array $existDataOrder Current order data
     * @return array
     */
    protected function buildOrder($name, $arguments, $existDataOrder)
    {
        if ($name === 'order') {
            $existDataOrder = $arguments;
        } else {
            foreach ($arguments as $index => $argument) {
                if ($existDataOrder && isset($existDataOrder[ $index ])) {
                    $existDataOrder[ $index ] = [
                        $existDataOrder[ $index ],
                        $argument,
                    ];
                }
            }
        }

        return $existDataOrder;
    }

    /**
     * Build conditions for request.
     *
     * @param array $arguments Arguments for build where condition
     * @return array
     */
    protected function buildCondition($arguments)
    {
        $whereCondition = [
            'type'       => 'group',
            'conditions' => [],
        ];

        $countArguments = count($arguments);

        foreach ($arguments as $index => $argument) {
            if (!is_array($arguments)) {
                continue;
            }
            $countPartsCondition = count($argument);

            if (!in_array($countPartsCondition, [3, 4])) {
                continue;
            }

            $whereCondition[ 'conditions' ][] = [
                'type'      => 'condition',
                'field'     => $argument[ 0 ],
                'condition' => $argument[ 1 ],
                'value'     => $argument[ 2 ],
            ];

            if ($index + 1 < $countArguments) {
                $joiner = ($countPartsCondition === 3) ? 'and' : $argument[ 3 ];
                $whereCondition[ 'conditions' ][] = $this->addConditionJoiner($joiner);
            }
        }

        return $whereCondition;
    }

    /**
     * Return joiner to where condition.
     *
     * @param string $joinerValue Value join operator
     * @return array
     */
    protected function addConditionJoiner($joinerValue) {
        return [
            'type'  => 'joiner',
            'value' => $joinerValue,
        ];
    }

    /**
     * Build calculating param for request.
     *
     * @param array $arguments List arguments
     * @return array
     */
    protected function buildCalculating($arguments)
    {
        $calculatings = [];

        foreach ($arguments as $argument) {
            $itemCalculating = [];
            $dataCalculating = explode('|', $argument);

            if (isset($dataCalculating[ 0 ]) && !empty($dataCalculating[ 0 ])) {
                $itemCalculating[ 'type' ] = $dataCalculating[ 0 ];
            }

            if (isset($dataCalculating[ 1 ]) && !empty($dataCalculating[ 1 ])) {
                $itemCalculating[ 'field' ] = $dataCalculating[ 1 ];
            }

            if ($itemCalculating) {
                $calculatings[] = $itemCalculating;
            }
        }

        return $calculatings;
    }

    /**
     * Build join param for request.
     *
     * @param array $arguments List arguments
     * @return array
     */
    protected function buildJoin($arguments)
    {
        $joins = [];

        foreach ($arguments as $join) {
            $structure = [];
            $dataJoin  = explode('|', $join);

            if (!isset($dataJoin[ 0 ], $dataJoin[ 1 ])) {
                continue;
            }

            $structure[ 'reference' ] = $dataJoin[ 0 ];

            $condition = explode(',', trim($dataJoin[ 1 ]));

            if (isset($condition[ 0 ], $condition[ 1 ], $condition[ 2 ])) {
                $structure[ 'where' ] = [
                    'field'     => $condition[ 0 ],
                    'condition' => $condition[ 1 ],
                    'value'     => $condition[ 2 ],
                ];

                $joins[] = $structure;
            }
        }

        return $joins;
    }

    /**
     * Build where condition for endpoint statistics.
     *
     * @return string
     */
    protected function buildWhereForStatistics()
    {
        $where = [];

        foreach ($this->parameters[ 'query' ][ 'where' ] as $partConditions) {
            if ($partConditions[ 'type' ] !== 'group') {
                continue;
            }

            foreach ($partConditions[ 'conditions' ] as $itemCondition) {
                if ($itemCondition[ 'type' ] !== 'condition') {
                    continue;
                }

                $where[] = sprintf('%s=%s', $itemCondition[ 'field' ], $itemCondition[ 'value' ]);
            }

        }

        return json_encode($where);
    }

    /**
     * Build order condition for endpoint statistics.
     *
     * @return string
     */
    protected function buildOrderForStatistics()
    {
        $order = array_pop($this->parameters[ 'query' ][ 'order' ]);

        if (!is_array($order)) {
            $order = [$order, 'asc'];
        }

        if (strpos($order[ 0 ], '|') !== false) {
            $order[ 0 ] = preg_replace('#\|.*$#', ' alpha', $order[ 0 ]);
        }

        return '"' . implode(' ', $order) . '"';
    }

    /**
     * Add condition to relations.
     *
     * @param object $objectRequestWith Object with relations
     * @param array $relations List relations with condition
     * @return void
     */
    protected function buildConditionForRelations(&$objectRequestWith, $relations)
    {
        $groupRelations = $this->groupRelationsWithCondition($relations);

        foreach ($groupRelations as $nameRelation => $itemRelation) {
            foreach ($objectRequestWith as &$itemWith) {
                if ($itemWith[ 'reference' ] === $nameRelation) {
                    $itemWith[ 'where' ] = [$this->buildCondition($itemRelation)];

                    break;
                }
            }
        }
    }

    /**
     * Group conditions by relations.
     *
     * @param array $relations List condition relations
     * @return array
     */
    private function groupRelationsWithCondition($relations)
    {
        $groupRelations = [];

        foreach ($relations as $itemRelation) {
            $nameRelation = preg_replace('#\..*#', '', $itemRelation[ 0 ]);
            $itemRelation[ 0 ] = preg_replace('#.*\.#', '', $itemRelation[ 0 ]);

            $groupRelations[ $nameRelation ][] = $itemRelation;

        }

        return $groupRelations;
    }
}
