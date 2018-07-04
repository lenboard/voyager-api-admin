<?php

namespace App\Support\Classes\Api;

use Illuminate\Http\Request;

class Pagination
{
    /**
     * HTML template for buttons.
     *
     * @var string
     */
    protected static $buttonTemplate = '<li%s>'.
        '<a aria-label="%s" href="%s" data-page="%s">'.
        '<span aria-hidden="true">%s</span>'.
        '</a></li>';

    /**
     * HTML template for pages.
     *
     * @var string
     */
    protected static $pageTemplate = '<li%s>'.
        '<a href="%s" data-page="%3$s">%3$s</a></li>';

    /**
     * Available per page limits selections.
     *
     * @var array
     */
    protected static $availableLimits = [10, 30, 50, 100];

    /**
     * Default limit.
     *
     * @var integer
     */
    protected static $defaultLimit = 30;

    /**
     * Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Pagination object.
     *
     * @var stdClass
     */
    protected $pagination;

    /**
     * Name page param in request.
     *
     * @var string
     */
    protected $paramPage;

    /**
     * Construct API pagination.
     *
     * @param  Request  $request
     * @param  array  $pagination
     * @return void
     */
    public function __construct(Request $request, $pagination, $paramPage = 'page')
    {
        $this->request = $request;
        $this->pagination = $pagination;
        $this->withButtons = true;
        $this->before = 3;
        $this->after = 3;
        $this->paramPage = $paramPage;
    }

    /**
     * Number of pages after current.
     *
     * @param  integer $value
     * @return static
     */
    public function after($value)
    {
        $this->after = (integer)$value;

        return $this;
    }

    /**
     * Number of pages before current.
     *
     * @param  integer $value
     * @return static
     */
    public function before($value)
    {
        $this->before = (integer)$value;

        return $this;
    }

    /**
     * Total number of pages to display including current.
     *
     * @param  integer $value
     * @return static
     */
    public function size($value)
    {
        $this->before = floor((integer)$value/2);
        $this->after = floor((integer)$value/2);

        return $this;
    }

    /**
     * Remove first, previous, next and last buttons from pagination.
     *
     * @return static
     */
    public function withoutButtons()
    {
        $this->withButtons = false;

        return $this;
    }

    /**
     * Get an array of pages for pagination.
     *
     * @return array
     */
    public function pages()
    {
        $pages = collect();

        $startPage = $this->currentPage() <= $this->before ?
            1 : $this->currentPage() - $this->before;
        $endPage = $this->currentPage() > ($this->lastPage() - $this->after) ?
            $this->lastPage() : $this->currentPage() + $this->after;

        for ($page = $startPage; $page <= $endPage; $page++) {
            $pages->put($page, $this->pageLink($page));
        }

        return $pages;
    }

    /**
     * Convert pagination to html.
     *
     * @return string
     */
    public function render()
    {
        // only one page, no need for pagination
        if ($this->total() === 1) {
            return '';
        }

        $html  = '<ul class="pagination">';

        if ($this->withButtons) {
            // append first and previous buttons
            $html .= $this->firstButton();
            $html .= $this->previousButton();
        }

        foreach ($this->pages() as $page => $link) {
            $html .= sprintf(
                self::$pageTemplate,
                $page == $this->currentPage() ?
                    ' class="active" style="pointer-events:none"' : '',
                htmlspecialchars($link),
                $page
            );
        }

        if ($this->withButtons) {
            // append next and last buttons
            $html .= $this->nextButton();
            $html .= $this->lastButton();
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Convert pagination to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get HTML for previous button.
     *
     * @param  string $icon
     * @return string
     */
    protected function previousButton($icon = '&lsaquo;')
    {
        return sprintf(
            self::$buttonTemplate,
            $this->previousPage() ? '' : ' class="disabled"',
            'Previous',
            $this->pageLink($this->previousPage()),
            $this->previousPage(),
            $icon
        );
    }

    /**
     * Get HTML for next button.
     *
     * @param  string $icon
     * @return string
     */
    protected function nextButton($icon = '&rsaquo;')
    {
        return sprintf(
            self::$buttonTemplate,
            $this->nextPage() ? '' : ' class="disabled"',
            'Next',
            htmlspecialchars($this->pageLink($this->nextPage())),
            $this->nextPage(),
            $icon
        );
    }

    /**
     * Get HTML for last button.
     *
     * @param  string $icon
     * @return string
     */
    protected function lastButton($icon = '&raquo;')
    {
        return sprintf(
            self::$buttonTemplate,
            $this->currentPage() == $this->lastPage() ?
                ' class="disabled"' : '',
            'Last',
            htmlspecialchars($this->pageLink($this->lastPage())),
            $this->lastPage(),
            $icon
        );
    }

    /**
     * Get HTML for first button.
     *
     * @param  string $icon
     * @return string
     */
    protected function firstButton($icon = '&laquo;')
    {
        return sprintf(
            self::$buttonTemplate,
            $this->currentPage() == $this->firstPage() ?
                ' class="disabled"' : '',
            'First',
            $this->pageLink($this->firstPage()),
            $this->firstPage(),
            $icon
        );
    }

    /**
     * Get first page number.
     *
     * @return integer
     */
    public function firstPage()
    {
        return 1;
    }

    /**
     * Get last page number.
     *
     * @return integer
     */
    public function lastPage()
    {
        return (integer)$this->pagination->total_pages;
    }

    /**
     * Get current page number.
     *
     * @return integer
     */
    public function currentPage()
    {
        return (integer)$this->pagination->page;
    }

    /**
     * Get previous page number.
     *
     * @return integer
     */
    public function previousPage()
    {
        $previousPage = $this->currentPage() - 1;

        if ($this->hasPage($previousPage)) {
            return $previousPage;
        }

        return null;
    }

    /**
     * Get next page number.
     *
     * @return integer
     */
    public function nextPage()
    {
        $nextPage = $this->currentPage() + 1;

        if ($this->hasPage($nextPage)) {
            return $nextPage;
        }

        return null;
    }

    /**
     * Check if page number exists.
     *
     * @param  integer $page
     * @return bool
     */
    public function hasPage($page)
    {
        if ($page < $this->firstPage()) {
            return false;
        }

        if ($page > $this->lastPage()) {
            return false;
        }

        return true;
    }

    /**
     * Check if previous page exists.
     *
     * @return bool
     */
    public function hasPrevious()
    {
        if (isset($this->pagination->links->previous)) {
            return true;
        }

        return false;
    }

    /**
     * Check if next page exists.
     *
     * @return bool
     */
    public function hasNext()
    {
        if (isset($this->pagination->links->next)) {
            return true;
        }

        return false;
    }

    /**
     * Get page offset.
     *
     * @return integer
     */
    public function offset()
    {
        if (isset($this->pagination->per_page)) {
            return ($this->currentPage() == 1) ?
                1 : ($this->limit() * ($this->currentPage() - 1) + 1);
        }

        return 0;
    }

    /**
     * Get number of elements on current page.
     *
     * @return integer
     */
    public function count()
    {
        if (isset($this->pagination->count)) {
            return $this->pagination->count;
        }

        return 0;
    }

    /**
     * Get page last element number.
     *
     * @return integer
     */
    public function end()
    {
        if ($this->total() <= $this->limit())
        {
            return $this->count();
        }

        return $this->offset() + $this->count();
    }

    /**
     * Get page limit.
     *
     * @return integer
     */
    public function limit()
    {
        if (isset($this->pagination->per_page)) {
            return (int)$this->pagination->per_page;
        }

        return 0;
    }

    /**
     * Get available per-page limits.
     *
     * @return array
     */
    public function limits()
    {
        return self::$availableLimits;
    }

    /**
     * Magic method to retrieve underlying pagination object properties.
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->pagination->$name)) {
            return null;
        }

        return $this->pagination->$name;
    }

    /**
     * Get total amount of items.
     *
     * @return integer
     */
    public function total()
    {
        if (isset($this->pagination->total_pages)) {
            return (int)$this->pagination->total_pages;
        }

        return 0;
    }

    /**
     * Get page link.
     *
     * @param  integer $page
     * @return string
     */
    protected function pageLink($page)
    {
        static $pageUrl = null;

        if ($pageUrl === null) {
            $pageUrl  = preg_replace(
                '/(\?|&)' . $this->paramPage . '=\d+/',
                '',
                $this->request->fullUrl()
            );

            if (
                substr_count($pageUrl, '&') == 1 &&
                strpos($pageUrl, '?') === false)
            {
                $pageUrl = str_replace('&', '?', $pageUrl);
            }

            $pageUrl .= (strpos($pageUrl, '?') === false ? '?' : '&') . $this->paramPage . '=';
        }

        return $pageUrl . $page;
    }

    /**
     * Resolve current page number from request.
     *
     * @param  Request  $request
     * @return integer
     */
    public static function resolveCurrentPage(Request $request)
    {
        static $page = null;

        if ($page === null) {
            $page = $request->input('page', null);
        }

        return $page;
    }

    /**
     * Resolve current limit from request.
     *
     * @param  Request  $request
     * @return integer
     */
    public static function resolveCurrentLimit(Request $request)
    {
        static $limit = null;

        if ($limit === null) {
            $limit = $request->input('limit', null);
        }

        return $limit;
    }

    /**
     * Get default limit.
     *
     * @return integer
     */
    public static function defaultLimit()
    {
        return (int)self::$defaultLimit;
    }
}
