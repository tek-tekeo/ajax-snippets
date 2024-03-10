<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;

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

  public function getAll()
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table);
    return collect($res)->map(function($app){
      return new App(
        new AppId((int)$app->id),
        (string)$app->name,
        (string)$app->img,
        (string)$app->dev,
        (string)$app->ios_link,
        (string)$app->android_link,
        (string)$app->web_link,
        (string)$app->ios_affi_link,
        (string)$app->android_affi_link,
        (string)$app->web_affi_link,
        (string)$app->article,
        (int)$app->app_order,
        (int)$app->app_price
      );
    })->toArray();
  }

  public function findById(AppId $appId)
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table." WHERE id=".$appId->getId());
    if(!$res == null){
      $app = new App(
        new AppId((int)$res->id),
        (string)$res->name,
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

  public function findByName(string $name): App | null
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table." WHERE name='".$name."'");
    if($res == null){
      return null;
    }
    return new App(
      new AppId((int)$res->id),
      (string)$res->name,
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
  }

  public function save(App $app) : AppId
  {
    $res = $this->db->replace( 
      $this->table, 
      $app->entity()
    );
    return new AppId($this->db->insert_id);
  }

  public function delete(AppId $appId) : bool
  {
    $result = $this->db->delete(
      $this->table,
      ['id' => $appId->getId()]
    );
    return $result;
  }
}