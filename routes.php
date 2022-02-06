<?php

namespace AjaxSnippets;

use Closure;
use Exception;
use AjaxSneppets\ServiceProviders\AppServiceProvider;

class Route
{
  const API_BASE_PATH = 'ajax_snippets_path/v1';

  public function __constructor()
  {
    // 
  }

  static public function get(string $path, string $callback)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'GET');
  }

  static public function post(string $path, string $callback)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'POST');
  }

  static public function put(string $path, string $callback)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'PUT');
  }

  static public function delete(string $path, string $callback)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'DELETE');
  }

  static public function resource(string $path, string $callbackClass)
  {
    // index, show
    self::get($path, $callbackClass . '@index');
    self::get($path . '/(?P<id>\d+)', $callbackClass . '@show');

    // create
    self::post($path, $callbackClass . '@create');

    // update
    self::put($path . '/(?P<id>\d+)', $callbackClass . '@update');

    // delete
    self::delete($path . '/(?P<id>\d)', $callbackClass . '@delete');
  }

  static private function registerMethods(string $path, string $callback, string $method)
  {
    $requestMethod = '';
    
    switch (strtoupper($method)) {
      case 'GET':
        $requestMethod = 'GET';
        break;
      case 'POST':
        $requestMethod = 'POST';
        break;
      case 'PUT':
        $requestMethod = 'PUT';
        break;
      case 'DELETE':
        $requestMethod = 'DELETE';
        break;
      default:
        throw new Exception('Invalid Method: ' . $method);
        break;
    }

    $options = [
      'methods' => $requestMethod,
      'callback' => self::parseMethod($callback),
      'permission_callback' => '__return_true',//Closure::fromCallable([new Authorization(), 'handle'])
    ];

    // エンドポイントの登録
    register_rest_route(self::API_BASE_PATH, $path, $options);
  }

  static private function parseMethod(string $method): Closure
  {
    if (strpos($method, "@")) {
      $callableArray = self::getClassAndMethod($method);
      return Closure::fromCallable($callableArray);
    }

    return Closure::fromCallable($method);
  }

  static private function getClassAndMethod(string $str): array
  {
    global $diContainer;
    $actions = explode('@', $str);
    $className = $actions[0];
    $actionName = $actions[1];
    
    //DI対応
    return [$diContainer->get($className), $actionName];
  }
}