<?php

namespace App\Support\Classes\Api;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Support\Classes\Api\Traits\MessageTrait;

class Response implements ResponseInterface
{
    use MessageTrait;

    /**
     * Response object container.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $container;

    /**
     * Reponse pagination instance.
     *
     * @var \App\Support\Classes\Api\Pagination
     */
    protected $pagination;

    /**
     * Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * JSON response container.
     *
     * @var object
     */
    protected $json = null;

    /**
     * Array response container.
     *
     * @var array
     */
    protected $array = null;

    /**
     * Construct API response.
     *
     * @param  ResponseInterface  $response
     * @param  Request  $request
     * @return void
     */
    public function __construct(ResponseInterface $response, Request $request)
    {
        $this->container = $response;
        $this->request = $request;
    }

    /**
     * Get raw response.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function raw()
    {
        return $this->container ?: null;
    }

    /**
     * Get JSON decoded body.
     *
     * @return object
     */
    public function json()
    {
        if ($this->json === null) {
            // store decoded json in variable to save extra processing
            $this->json = json_decode((string)$this->container->getBody());
        }

        return $this->json;
    }

    /**
     * Except attributes from response data.
     *
     * @param array|string $exceptAttributes List delete attributes from response
     * @param bool $returnArray Flag is need return array response
     * @return StdClass|array
     */
    public function except($exceptAttributes, $returnArray = false)
    {
        $responseData = $this->toArray();

        if (is_string($exceptAttributes)) {
            $exceptAttributes = explode(',', $exceptAttributes);
        }

        if (!is_array($exceptAttributes)) {
            if ($returnArray) {
                return $responseData;
            }

            return json_encode(json_decode($responseData));
        }

        foreach ($exceptAttributes as $attribute) {
            $relations = explode('.', $attribute);

            $this->exceptField($responseData, $relations);
        }

        if ($returnArray) {
            return $responseData;
        }

        return json_decode(json_encode($responseData));
    }

    /**
     * Except all attributes without some attributes.
     *
     * @param array|string $onlyonlyAttributes List attributes wich need stay in array
     * @param bool $returnArray Flag is need return array structure, by default is false
     * @return StdClass|array
     */
    public function only($onlyAttributes, $returnArray = false)
    {
        $responseData = $this->toArray();

        if (is_string($onlyAttributes)) {
            $onlyAttributes = explode(',', $onlyAttributes);
        }

        if (!is_array($onlyAttributes)) {
            if ($returnArray) {
                return $responseData;
            }

            return json_encode(json_decode($responseData));
        }

        $tree = [];

        foreach ($onlyAttributes as $attribute) {
            $this->buildTree(explode('.', $attribute), 0, $tree);
        }

        $this->recursiveStayOnly($responseData, $tree);

        if ($returnArray) {
            return $responseData;
        }

        return json_decode(json_encode($responseData));
    }

    /**
     * Get data part of response.
     *
     * @return object|null
     */
    public function data()
    {
        if ($this->isEmpty() || !$this->hasData()) {

            return null;
        }

        return $this->json()->data;
    }

    /**
     * Get meta part of response.
     *
     * @return object|null
     */
    public function meta()
    {
        if ($this->isEmpty() || !$this->hasMeta()) {
            return null;
        }

        return $this->json()->meta;
    }

    /**
     * Get by name.
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        return data_get($this->json(), $name, null);
    }

    /**
     * Retrive first data item.
     *
     * @return object
     */
    public function first()
    {
        if ($this->isEmpty() || !$this->hasData()) {
            return null;
        }

        return head($this->data());
    }

    /**
     * Get pagination.
     *
     * @return string|null
     */
    public function pagination($paramPage = 'page')
    {
        if (isset($this->pagination)) {
            return $this->pagination;
        }

        if (!$this->has('pagination')) {
            return null;
        }

        // persist pagination for response
        $this->pagination = new Pagination(
            $this->request,
            $this->get('pagination'),
            $paramPage
        );

        return $this->pagination;
    }

    /**
     * Checks for empty response.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->json() === null ? true : false);
    }

    /**
     * Checks if response has data.
     *
     * @return bool
     */
    public function hasData()
    {
        $response = $this->json();

        if ($response && isset($response->data)) {
            return true;
        }

        return false;
    }

    /**
     * Cast body to array.
     *
     * @return array
     */
    public function toArray()
    {
        if (!is_null($this->array)) {
            return $this->array;
        }

        $this->array = json_decode(json_encode($this->json()), true);

        return $this->array;
    }

    /**
     * Transform body to collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function toCollection()
    {
        return collect($this->toArray());
    }

    /**
     * Check if response has key.
     *
     * @param  string $key
     * @return bool
     */
    public function has($key)
    {
        return !$this->isEmpty() && data_get($this->json(), $key, null);
    }

    /**
     * Get response value by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function getValue($key)
    {
        return array_get($this->toArray(), $key);
    }

    /**
     * Get response status code()
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->container->getStatusCode();
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * @param  integer  $code
     * @param  string  $reasonPhrase
     * @return static
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return $this->container->withStatus($code, $reasonPhrase);
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * @return string
     */
    public function getReasonPhrase()
    {
        return $this->container->getReasonPhrase();
    }

    /**
     * Gets response properties through magic.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->json()) {
            return null;
        }

        return $this->json()->$name;
    }

    /**
     * Except field.
     *
     * @param array $array Array structure
     * @param array $path Path for except field.
     * @return void
     */
    private function exceptField(&$array, $path)
    {
        if (empty($path)) {
            return;
        }

        $currentPath = array_shift($path);

        if (array_key_exists($currentPath, $array[ 'data' ])) {
            unset($array[ 'data' ][ $currentPath ]);
        } else if (isset($array[ 'data' ][ 0 ][ 'data' ]) && array_key_exists($currentPath, $array[ 'data' ][ 0 ][ 'data' ])) {
            foreach ($array[ 'data' ] as &$element) {
                unset($element[ 'data' ][ $currentPath ]);
            }
        } else if (isset($array[ 'data' ][ 0 ]) && array_key_exists($currentPath, $array[ 'data' ][ 0 ])) {
            foreach ($array[ 'data' ] as &$element) {
                $this->exceptField($element[ $currentPath ], $path);
            }
        }

        if (isset($array[ $currentPath ])) {
            $this->exceptField($array[ $currentPath ], $path);
        }
    }

    /**
     * Build tree structure from string.
     *
     * @param array $entry Entry array
     * @param int $depth Depth of array
     * @param array $current Current array
     * @return void
     */
    private function buildTree($entry, $depth, &$current)
    {
        if ($depth < count($entry)) {
            $key = $entry[ $depth ];

            if (!isset($current[$key])) {
                $current[ $key ] = null;
            }

            call_user_func_array([$this, __METHOD__], [$entry, $depth + 1, &$current[ $key ]]);
        }
    }

    /**
     * Recursive except fields from array structure.
     *
     * @param array $array Array structure
     * @param array $onlyAttributes Array list attributes wich need stay in array
     * @return void
     */
    private function recursiveStayOnly(&$array, $onlyAttributes)
    {
        $listStayAttributes = [];

        foreach ($onlyAttributes as $key => $onlyAttribute) {
            if (is_array($onlyAttribute)) {
                if (isset($array[ 'data' ][ 0 ])) {
                    foreach ($array[ 'data' ] as &$itemData) {
                        call_user_func_array([$this, __METHOD__], [&$itemData[ $key ], $onlyAttribute]);
                    }
                } else {
                    call_user_func_array([$this, __METHOD__], [&$array[ $key ], $onlyAttribute]);
                }
            } else {
                $listStayAttributes[] = $key;
            }
        }

        $this->exceptAllWithoutOnly($array, $listStayAttributes);
    }

    /**
     * Delete attributes from array.
     *
     * @param array $array Array list
     * @param array $onlyAttributes List only attributes
     * @return void
     */
    private function exceptAllWithoutOnly(&$array, $onlyAttributes)
    {
        if (isset($array[ 'data' ][ 0 ])) {
            foreach ($array[ 'data' ] as $key => &$itemArray) {
                call_user_func_array([$this, __METHOD__], [&$array[ 'data' ][ $key ], $onlyAttributes]);
            }
        } else {
            foreach ($array[ 'data' ] as $fieldName => $value) {
                if (array_search($fieldName, $onlyAttributes) === false) {
                    unset($array[ 'data' ][ $fieldName ]);
                }
            }
        }
    }
}
