<?php

if (! function_exists('api')) {
    /**
     * Get api client instance.
     *
     * @param  string|null $endpoint
     * @param  bool        $authenticate
     * @return \Support\Classes\Api\Api
     */
    function api($endpoint = null, $authenticate = false)
    {
        if ($endpoint === null) {
            return app('api.client');
        }

        return app('api.client')->request($endpoint, $authenticate);
    }
}

if (! function_exists('echo_meta')) {
    /**
     * Get echo server url.
     *
     * @return string
     */
    function echo_meta()
    {
        $config = config()->get('echo');

        $meta = sprintf(
            '<meta name="echo-server" content="%s:%s">',
            $config['host'],
            $config['port']
        );

        if (isset($config['transports'])) {
            $meta .=  "\n\t\t" . sprintf(
                '<meta name="echo-transports" content="%s">',
                implode(',', $config['transports'])
            );
        }

        if (isset($config['reconnect'])) {
            $meta .=  "\n\t\t" . sprintf(
                '<meta name="echo-reconnect" content="%s">',
                (int)$config['reconnect']
            );
        }

        if (isset($config['reconnect-delay'])) {
            $meta .= "\n\t\t" . sprintf(
                '<meta name="echo-reconnect-delay" content="%s">',
                (int)$config['reconnect-delay']
            );
        }

        if (isset($config['reconnect-attempts'])) {
            $meta .= "\n\t\t" . sprintf(
                '<meta name="echo-reconnect-attempts" content="%s">',
                (int)$config['reconnect-attempts']
            );
        }

        return $meta;
    }
}

if (! function_exists('captcha_img')) {
    /**
     * Get captcha image.
     *
     * @return string
     */
    function captcha_img()
    {
        return api()->captcha()->html();
    }
}

if (! function_exists('captcha_field')) {
    /**
     * Get captcha input field.
     *
     * @param  string  $classes
     * @return string
     */
    function captcha_field($classes = '')
    {
        return api()->captcha()->input(explode(' ', $classes));
    }
}

if (! function_exists('check_captcha')) {
    /**
     * Check captcha value.
     *
     * @param  string  $value
     * @return bool
     */
    function check_captcha($value)
    {
        return api()->captcha()->check($value);
    }
}
