<?php

namespace Slova\Core\Route;

use Symfony\Component\Routing\Matcher\UrlMatcher;

class Observer
{

    protected $matcher;


    public function __construct(UrlMatcher $matcher)
    {
        $this->matcher = $matcher;
    }

    public function kernelRequest($event)
    {
        $request = $event->get('request');


        if ($request->attributes->has('_controller')) {
            // routing is already done
            return;
        }

        // add attributes based on the request (routing)
        //try {
            $parameters = $this->matcher->matchRequest($request);
            $request->attributes->add($parameters);
            unset($parameters['_route']);
            unset($parameters['_controller']);
            $request->attributes->set('_route_params', $parameters);
        //} catch (\Exception $e) {
            // toDo catch specific exception and throw route exception
        //}
    }
}
