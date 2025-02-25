<?php

namespace App\Security\Http;

use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\Request;

class CookieTokenExtractor implements TokenExtractorInterface
{


    private string $cookieName;

    /**
     * @param string $cookieName
     */
    public function __construct(string $cookieName)
    {
        $this->cookieName = $cookieName;
    }

    public function extract(Request $request) : ?string
    {
        return $request->cookies->get($this->cookieName);
    }
}