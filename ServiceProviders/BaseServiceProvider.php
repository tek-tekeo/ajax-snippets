<?php

namespace AjaxSnippets\ServiceProviders;

class BaseServiceProvider
{
    public function register($reflectionClass)
    {
        $reflection = new \ReflectionClass($reflectionClass);
        $args = [];
        if ($reflection->hasMethod('__construct')) {
            $parameters = $reflection->getConstructor()->getParameters();
            
            $args = array_reduce($parameters, function ($args, $parameter) {
                if ($reflectionClass = $parameter->getClass()) {
                    $className = $reflectionClass->getName();
                    print_r(new $className());die;
                    $args[$parameter->getName()] = $className::getInstance();
                }
                print_r($args);die;
                return $args;
            }, []);
        }
        $reflection->newInstanceArgs($args);
    }
}