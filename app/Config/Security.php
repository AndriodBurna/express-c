<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Security extends BaseConfig
{
    /**
     * CSRF Protection Method
     * Values: 'cookie' or 'session'
     */
    public string $csrfProtection = 'cookie';

    /**
     * Randomize the CSRF Token
     */
    public bool $tokenRandomize = false;

    /**
     * CSRF Token Name (name of the POST field / header key)
     */
    public string $tokenName = 'csrf_token';

    /**
     * CSRF Header Name (used for AJAX requests)
     */
    public string $headerName = 'X-CSRF-TOKEN';

    /**
     * CSRF Cookie Name
     */
    public string $cookieName = 'csrf_cookie_name';

    /**
     * CSRF Expire time in seconds
     */
    public int $expires = 7200;

    /**
     * Regenerate the CSRF token after each successful validation
     */
    public bool $regenerate = true;

    /**
     * Redirect on CSRF failure instead of throwing
     */
    public bool $redirect = false;

    /**
     * URIs that skip CSRF (e.g., webhooks, API callbacks)
     */
    public array $except = [];

    //--------------------------------------------------------------------
    // Cookie Settings (used when csrfProtection = 'cookie')
    //--------------------------------------------------------------------

    public bool $cookieSecure   = false;
    public bool $cookieHTTPOnly = false;
    public string $samesite     = 'Lax';
}