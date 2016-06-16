<?php

namespace EventBundle\Utils;


use Symfony\Component\HttpFoundation\Request;

class JsonContentType
{
    /**
     * @param Request $request
     * @return bool
     */
    public function isJsonContentType(Request $request)
    {
        return substr($request->headers->get('Content-Type'), 0, 16) === 'application/json' ? true : false;
    }
}