<?php
/**
 * Klein (klein.php) - A fast & flexible router for PHP
 *
 * @author      Chris O'Hara <cohara87@gmail.com>
 * @author      Trevor Suarez (Rican7) (contributor and v2 refactorer)
 * @copyright   (c) Chris O'Hara
 * @link        https://github.com/klein/klein.php
 * @license     MIT
 */

namespace Klein\DataCollection;

use Klein\ResponseCookie;

/**
 * ResponseCookieDataCollection
 *
 * A DataCollection for HTTP response cookies
 */
class ResponseCookieDataCollection extends DataCollection
{

    /**
     * Methods
     */

    /**
     * Constructor
     *
     * @override (doesn't call our parent)
     * @param array $cookies The cookies of this collection
     */
    public function __construct(array $cookies = array())
    {
        parent::__construct($cookies);

        foreach ($cookies as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Set a cookie
     *
     * {@inheritdoc}
     *
     * A value may either be a string or a ResponseCookie instance
     * String values will be converted into a ResponseCookie with
     * the "name" of the cookie being set from the "key"
     *
     * Obviously, the developer is free to organize this collection
     * however they like, and can be more explicit by passing a more
     * suggested "$key" as the cookie's "domain" and passing in an
     * instance of a ResponseCookie as the "$value"
     *
     * @param string $key The name of the cookie to set
     * @param mixed $value The value of the cookie to set
     * @see DataCollection::set()
     */
    public function set(string $key, mixed $value): DataCollection
    {
        if (!$value instanceof ResponseCookie) {
            $value = new ResponseCookie($key, $value);
        }

        return parent::set($key, $value);
    }
}
