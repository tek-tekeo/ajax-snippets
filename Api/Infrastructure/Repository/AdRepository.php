<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;

class AdRepository implements IAdRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX . 'ads';
  }

  public function save(Ad $ad) : AdId
  {
    $res = $this->db->replace( 
      $this->table, 
      $ad->entity()
    );
    return new AdId($this->db->insert_id);
  }

  public function findById(AdId $adId) : Ad
  {
    $row = $this->db->get_row("SELECT * FROM ".$this->table." WHERE id = ".$adId->getId(). " AND deleted_at IS NULL");
    if(!$row == null){
      $ad = new Ad(
        new AdId($row->id),
        $row->name,
        $row->anken,
        $row->affi_link,
        $row->s_link,
        new AspId($row->asp_id),
        $row->affi_img,
        $row->img_tag,
        $row->s_img_tag,
        $row->affi_img_width,
        $row->affi_img_height,
        new AppId($row->app_id) ?? new AppId(0)
      );
      return $ad;
    }
    throw new \Exception('idに該当する親要素がありません。');
    return null;
  }

  public function findByName(string $name) : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table."  WHERE name LIKE '%".$name."%' AND deleted_at IS NULL");
    return collect($res)->map(function($row){
      return new Ad(
        new AdId($row->id),
        $row->name,
        $row->anken,
        $row->affi_link,
        $row->s_link,
        new AspId($row->asp_id),
        $row->affi_img,
        $row->img_tag,
        $row->s_img_tag,
        $row->affi_img_width,
        $row->affi_img_height,
        new AppId($row->app_id) ?? new AppId(0)
      );
    })->toArray();
  }

  public function findAll() : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table. " WHERE deleted_at IS NULL");
    return collect($res)->map(function($row){
      return new Ad(
        new AdId($row->id),
        $row->name,
        $row->anken,
        $row->affi_link,
        $row->s_link,
        new AspId((int)$row->asp_id),
        $row->affi_img,
        $row->img_tag,
        $row->s_img_tag,
        $row->affi_img_width,
        $row->affi_img_height,
        new AppId($row->app_id) ?? new AppId(0)
      );
    })->toArray();
  }

  public function delete(AdId $adId) : bool
  {
    $res = $this->db->update(
      $this->table,
      ['deleted_at'=> date('Y-m-d')],
      ['id' => $adId->getId()]
    );
    return $res;
  }
}