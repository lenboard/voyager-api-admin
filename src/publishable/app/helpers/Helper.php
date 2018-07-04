<?php

namespace App\helpers;

class Helper
{
    /**
     * Modify parameters for "with" request.
     *
     * @param string $arguments
     * @return array JSON encode with parameters
     */
    public static function modifyWithArguments($arguments)
    {
        $finishArguments = [];

        foreach ($arguments as $listRelations) {
            $levels           = [];
            $includeRelations = explode('::', $listRelations);
            $dotterRelations  = preg_replace('#:\w+\((.*?)\)#', '', $listRelations);

            foreach ($includeRelations as $index => $nameRelation) {

                $levels[ $index ] = [
                    'path'       => implode('.', array_slice($includeRelations, 0, $index + 1)),
                    'order'      => self::parseOrderForRelationRequest($nameRelation),
                    'limit'      => self::parseLimitForRelationRequest($nameRelation),
                    'where'      => self::buildWhereForRelationRequest($nameRelation),
                    'pagination' => self::parsePaginationForRelationRequest($nameRelation),
                    //'join'  => self::parseJoinForRelationRequest($nameRelation),
                ];
            }

            self::fillWithRequestArguments($finishArguments, $dotterRelations, $levels);
        }

        self::replaceKeysInArrayToNumbers($finishArguments, $dotterRelations);

        return $finishArguments;
    }

    /**
     * Build array structure for request with relations.
     *
     * @param array $finishArray Array list all builds relations
     * @param string $dotterRelations Relation path for add current arguments
     * @param array $levels List add arguments to list all arguments
     * @return void
     */
    private static function fillWithRequestArguments(&$finishArray, $dotterRelations, $levels)
    {
        $dotterRelations = explode('::', $dotterRelations);
        $path = '';

        foreach ($dotterRelations as $index => $relationName) {
            if ($path) {
                $path .= '.with.' . $relationName;
            } else {
                $path = $relationName;
            }

            $data = [
                'reference' => $relationName,
            ];

            // add order for relation
            foreach ($levels[ $index ][ 'order' ] as $order) {
                $data[ 'order' ][] = [
                    $order[ 'column' ], $order[ 'type' ],
                ];
            }

            // add pagination for relation
            if ($levels[ $index ][ 'pagination' ]) {
                $data[ 'pagination' ] = $levels[ $index ][ 'pagination' ];
            }

            // add limit for relation
            if ($levels[ $index ][ 'limit' ] !== null) {
                $data[ 'pagination' ] = [
                    'page'     => 1,
                    'per_page' => $levels[ $index ][ 'limit' ],
                ];
            }

            // add where for relation
            if ($levels[ $index ][ 'where' ]) {
                $data[ 'where' ] = $levels[ $index ][ 'where' ];
            }

            //if ($levels[ $index ][ 'join' ]) {
            //    $data[ 'join' ] = $levels[ $index ][ 'join' ];
            //}

            if (!array_has($finishArray, $path)) {
                array_set($finishArray, $path, $data);
            } else {
                array_add($finishArray, $path, $data);
            }
        }
    }

    /**
     * Get order for relations in "with" request.
     *
     * @param string $str Parse string for order
     * @return array List orders
     */
    public static function parseOrderForRelationRequest($str)
    {
        $patternOrder = '#:order\((.*?)\)#';
        $order = [];

        preg_match_all($patternOrder, $str, $matches);

        if (empty($matches) || !isset($matches[ 1 ])) {
            return $order;
        }

        foreach ($matches[ 1 ] as $index => $match) {

            $configureOrder = explode('|', $match);

            if (isset($configureOrder[ 0 ])) {
                $order[ $index ][ 'column' ] = $configureOrder[ 0 ];

                if (
                    isset($configureOrder[ 1 ]) &&
                    in_array(strtolower($configureOrder[ 1 ]), ['asc', 'desc'])
                ) {
                    $order[ $index ][ 'type' ] = $configureOrder[ 1 ];
                }
            }
        }

        return $order;
    }

    /**
     * Get limit for relations in "with" request.
     *
     * @param string $str Parse string for limit
     * @return string
     */
    public static function parseLimitForRelationRequest($str)
    {
        $patternLimit = '#:limit\((.*?)\)#';

        $limit = null;

        preg_match($patternLimit, $str, $match);

        if (empty($match) || !isset($match[ 0 ])) {
            return $limit;
        }

        $limit = preg_replace($patternLimit, '$1', $match[ 0 ]);

        return $limit;
    }

    /**
     * Replace associative array arguments to numeric array for request.
     *
     * @param array $finishArray List array arguments
     * @return void
     */
    public static function replaceKeysInArrayToNumbers(&$finishArray)
    {
        $i = 0;

        foreach ($finishArray as $key => $item) {
            if (array_key_exists('with', $item)) {

                self::replaceKeysInArrayToNumbers($item[ 'with' ]);
            }

            $finishArray[ $i ] = $item;
            array_forget($finishArray, $key);

            $i++;
        }
    }

    /**
     * Parse and build structure where condition for relations.
     *
     * @param string $str String for parse where condition
     * @return array
     */
    private static function buildWhereForRelationRequest($str)
    {
        $patternWhere     = '#:where\((.*?)\)#';
        $patternOrWhere   = '#:orWhere\((.*?)\)#';
        $patternAndWhere  = '#:andWhere\((.*?)\)#';
        $where = $matches = [];

        preg_match_all($patternWhere, $str, $matches);

        if (isset($matches[ 1 ]) && !empty($matches[ 1 ])) {
            self::buildCondition($where, $matches);
        }

        $matches = [];

        preg_match_all($patternOrWhere, $str, $matches);

        if (isset($matches[ 1 ]) && !empty($matches[ 1 ])) {
            if ($where) {
                $where[] = [
                    'type'  => 'joiner',
                    'value' => 'or',
                ];
            }

            self::buildCondition($where, $matches, false);
        }

        $matches = [];

        preg_match_all($patternAndWhere, $str, $matches);

        if (isset($matches[ 1 ]) && !empty($matches[ 1 ])) {
            if ($where) {
                $where[] = [
                    'type'  => 'joiner',
                    'value' => 'and',
                ];
            }

            self::buildCondition($where, $matches, false);
        }

        return $where;
    }

    /**
     * Buidl where condition.
     *
     * @param array $whereArray
     * @param array $matches
     * @param bool $where
     * @return void
     */
    private static function buildCondition(&$whereArray, $matches, $where = true)
    {
        foreach ($matches[ 1 ] as $match) {
            if ($whereArray && $where) {
                $whereArray[] = [
                    'type'  => 'joiner',
                    'value' => 'and',
                ];
            }
            $partConditionWheres = explode(']', $match);
            $conditions = [];

            foreach ($partConditionWheres as $index => $condition) {
                if (empty($condition)) {
                    continue;
                }
                $condition = explode(',', trim(str_replace(['[', ' '], '', $condition), ','));
                $countConditionArguments = count($condition);

                if ($countConditionArguments === 3) {
                    $conditions[] = [
                        'type'      => 'condition',
                        'field'     => $condition[ 0 ],
                        'condition' => $condition[ 1 ],
                        'value'     => $condition[ 2 ],
                    ];
                }

                if ($countConditionArguments === 4) {
                    $conditions[] = [
                        'type'  => 'joiner',
                        'value' => $condition[ 0 ],
                    ];
                    $conditions[] = [
                        'type'      => 'condition',
                        'field'     => $condition[ 1 ],
                        'condition' => $condition[ 2 ],
                        'value'     => $condition[ 3 ],
                    ];
                }
            }

            if ($conditions) {
                $whereArray[] = [
                    'type'       => 'group',
                    'conditions' => $conditions,
                ];
            }
        }
    }

    /**
     * Parse and build structure join condition for relations.
     *
     * @param string $str String for parse join condition
     * @return array
     */
    private static function parseJoinForRelationRequest($str)
    {
        $patternJoin = '#:join\((.*?)\)#';
        $join = $mathces = [];

        preg_match_all($patternJoin, $str, $matches);

        if (!isset($matches[ 1 ]) || empty($matches[ 1 ])) {
            return $join;
        }

        foreach ($matches[ 1 ] as $join) {
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

                $join[] = $structure;
            }
        }

        return $join;
    }

    /**
     * Parse and build structure pagination for relations.
     *
     * @param string $str String for parse pagination
     * @return array
     */
    private static function parsePaginationForRelationRequest($str)
    {
        $patternPagination = '#:pagination\((.*?)\)#';
        $pagination = [];

        preg_match($patternPagination, $str, $match);

        if (empty($match) || !isset($match[ 0 ])) {
            return $pagination;
        }

        $dataPagination = explode(',', preg_replace($patternPagination, '$1', $match[ 0 ]));

        if (isset($dataPagination[ 0 ], $dataPagination[ 1 ])) {
            $pagination = [
                'page' => $dataPagination[ 0 ],
                'per_page' => $dataPagination[ 1 ],
            ];
        }

        return $pagination;
    }
}
