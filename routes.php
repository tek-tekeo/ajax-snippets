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

  static public function get(string $path, string $callback, bool $adminPermission = true)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'GET', $adminPermission);
  }

  static public function post(string $path, string $callback, bool $adminPermission =true)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'POST', $adminPermission);
  }

  static public function put(string $path, string $callback, bool $adminPermission =true)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'PUT', $adminPermission);
  }

  static public function delete(string $path, string $callback, bool $adminPermission =true)
  {
    // エンドポイントの登録
    self::registerMethods($path, $callback, 'DELETE', $adminPermission);
  }

  static public function resource(string $path, string $callbackClass, bool $adminPermission =true)
  {
    // index, show
    self::get($path, $callbackClass . '@index', $adminPermission);
    self::get($path . '/(?P<id>\d+)', $callbackClass . '@show', $adminPermission);

    // create
    self::post($path, $callbackClass . '@create', $adminPermission);

    // update
    self::put($path . '/(?P<id>\d+)', $callbackClass . '@update', $adminPermission);

    // delete
    self::delete($path . '/(?P<id>\d)', $callbackClass . '@delete', $adminPermission);
  }

  static private function registerMethods(string $path, string $callback, string $method, bool $adminPermission =true)
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
      'permission_callback' => self::rest_permission($adminPermission),//Closure::fromCallable([new Authorization(), 'handle'])
    ];

    // エンドポイントの登録
    register_rest_route(self::API_BASE_PATH, $path, $options);
  }

  static private function rest_permission(bool $adminPermission)
  {
    if(!$adminPermission){
      return '__return_true';
    }
    return !current_user_can('manage_options');
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