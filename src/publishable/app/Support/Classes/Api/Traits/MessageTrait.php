<?php

namespace App\Support\Classes\Api\Traits;

use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->container->getProtocolVersion();
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * @param  string  $version
     * @return static
     */
    public function withProtocolVersion($version)
    {
        return $this->container->withProtocolVersion($version);
    }

    /**
     * Retrieves all message header values.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->container->getHeaders();
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param  string  $name
     * @return bool
     */
    public function hasHeader($name)
    {
        return $this->container->hasHeader($name);
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * @param  string  $name
     * @return array
     */
    public function getHeader($name)
    {
        return $this->container->getHeader($name);
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * @param  string  $name
     * @return string
     */
    public function getHeaderLine($name)
    {
        return $this->container->getHeaderLine($name);
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * @param  string  $name
     * @param  string|array  $value
     * @return static
     */
    public function withHeader($name, $value)
    {
        return $this->container->withHeader($name, $value);
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * @param  string|array  $value
     * @return static
     */
    public function withAddedHeader($name, $value)
    {
        return $this->container->withAddedHeader($name, $value);
    }

    /**
     * Return an instance without the specified header.
     *
     * @param  string  $name
     * @return static
     */
    public function withoutHeader($name)
    {
        return $this->container->withoutHeader($name);
    }

    /**
     * Gets the body of the message.
     *
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody()
    {
        return $this->container->getBody();
    }

    /**
     * Return an instance with the specified message body.
     *
     * @param  \Psr\Http\Message\StreamInterface $body
     * @return static
     */
    public function withBody(StreamInterface $body)
    {
        return $this->container->withBody($body);
    }
}
