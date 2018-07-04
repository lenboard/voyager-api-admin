<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Traits\UsesApi;
use App\Support\Classes\Api\Pagination;

/**
 * Controller for user securities api endpoints.
 */
class SecuritiesController extends Controller
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
     * Display page securities for authorized user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $maxIps         = 5;
        $whereCondition = [];

        $securitiesQuery = $this->api()
            ->profileSecurities()
            ->with('user')
            ->pagination($request->get('page'), $request->get('perPage'));

        for ($i = 1; $i <= $maxIps; $i++) {
            $ip = $request->get("ip_{$i}");

            if ($ip) {
                $whereCondition[] = ['ip', '=', $ip, 'or'];
            }
        }

        if ($whereCondition) {
            $lastElement = array_pop($whereCondition);
            unset($lastElement[ 3 ]);
            $whereCondition[] = $lastElement;

            for($i = count($whereCondition) + 1; $i <= $maxIps; $i++) {
                $whereCondition[] = [];
            }
            $securitiesQuery = $securitiesQuery->where(
                $whereCondition[ 0 ],
                $whereCondition[ 1 ],
                $whereCondition[ 2 ],
                $whereCondition[ 3 ],
                $whereCondition[ 4 ]
            );
        }

        $securities = $securitiesQuery->get();

        $pagination = $securities->pagination();

        return view('api.securities.index', compact('securities', 'pagination', 'maxIps'));
    }

    /**
     * Show one security by id for authorized user.
     *
     * @param integer $id Security id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $security = $this->api()
            ->profileSecurity($id)
            ->get();

        return view('api.securities.show', compact('security'));
    }

    /**
     * Create new securities for authorized user.
     *
     * @param \Illuminate\Http\Request $request Request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->api()
            ->profileSecurityCreate()
            ->post([
                'browser' => $request->header('User-Agent'),
                'ip'      => $request->ip(),
            ])
            ->json();

        return redirect()->route('api.securities.index');
    }

    /**
     * Update securities by id for authorized user.
     *
     * @param integer $id Securities id
     * @param \Illuminate\Http\Request $request Request
     * @return string JSON response view security
     */
    /*public function update($id, Request $request)
    {
        return response()->json(
            $this->api()
                ->profileSecurity($id)
                ->post([
                    'browser' => $request->header('User-Agent'),
                    'ip'      => $request->ip(),
                ])
                ->json()
        );
    }*/
}
