<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\App;

class AppRepository implements IAppRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'apps';
  }

  public function AppFindById(int $appId)
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table." WHERE app_id=".$appId);
    if(!$res == null){
      $app = new App(
        (int)$res->app_id,
        (string)$res->img,
        (string)$res->dev,
        (string)$res->ios_link,
        (string)$res->android_link,
        (string)$res->web_link,
        (string)$res->ios_affi_link,
        (string)$res->android_affi_link,
        (string)$res->web_affi_link,
        (string)$res->article,
        (int)$res->app_order,
        (int)$res->app_price
      );
      return $app;
    }
    return null;
  }

  public function saveApp(App $app) : bool
  {
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'app_id' => $app->appId(),
        'img' => $app->img(),
        'dev' => $app->dev(),
        'ios_link' => $app->iosLink(),
        'android_link' => $app->androidLink(),
        'web_link' => $app->webLink(),
        'ios_affi_link' => $app->iosAffiLink(),
        'android_affi_link' => $app->androidAffiLink(),
        'article' => $app->article(),
        'app_order' => $app->appOrder(),
        'app_price' => $app->appPrice()
      ), 
      array( 
        '%d',
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%d', 
        '%d' 
      )
    );
    return $res;
  }
}