<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class AdDetailRepository implements IAdDetailRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'ad_details';
  }

  public function findByName(string $name) : array
  {
	  $sql = "SELECT D.*, B.name as name FROM ". PLUGIN_DB_PREFIX."ads As B RIGHT JOIN ". PLUGIN_DB_PREFIX."ad_details As D ON B.id = D.ad_id where D.item_name LIKE '%".$name."%' OR B.name LIKE '%".$name."%' order by B.name asc, D.item_name asc";
    
    $res = $this->db->get_results($sql);
    return collect($res)->map(function($r){
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rchart,
        (string)$r->info,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent
      );
    })->toArray();
  }

  public function findById(AdDetailId $adDetailId): AdDetail
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table." WHERE id=".$adDetailId->getId());
    if(!$res == null){				
      return new AdDetail(
        new AdDetailId((int)$res->id),
        new AdId((int)$res->ad_id),
        (string)$res->item_name,
        (string)$res->official_item_link,
        (string)$res->affi_item_link,
        (string)$res->detail_img,
        (string)$res->amazon_asin,
        (string)$res->rakuten_id,
        (string)$res->rchart,
        (string)$res->info,
        (string)$res->review,
        (int)$res->is_show_url,
        (int)$res->same_parent
      );
    }
    throw new \Exception('Ad Detail IDに該当するデータが存在しません。', 500);
  }

  public function findLatest(){
    $res = $this->db->get_row("SELECT * FROM ".$this->table." ORDER BY id DESC LIMIT 1");
    if(!$res == null){				
      return new AdDetail(
        new AdDetailId((int)$res->id),
        new AdId((int)$res->ad_id),
        (string)$res->item_name,
        (string)$res->official_item_link,
        (string)$res->affi_item_link,
        (string)$res->detail_img,
        (string)$res->amazon_asin,
        (string)$res->rakuten_id,
        (string)$res->rchart,
        (string)$res->info,
        (string)$res->review,
        (int)$res->is_show_url,
        (int)$res->same_parent
      );
    }
    throw new Exception('Detail IDに該当するデータが存在しません。');
  }

  public function findByAdId(AdId $adId): array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table." WHERE ad_id=".$adId->getId()." ORDER BY id ASC");
    return collect($res)->map(function($r){
      return new AdDetail(
        new AdDetailId((int)$r->id),
        new AdId((int)$r->ad_id),
        (string)$r->item_name,
        (string)$r->official_item_link,
        (string)$r->affi_item_link,
        (string)$r->detail_img,
        (string)$r->amazon_asin,
        (string)$r->rakuten_id,
        (string)$r->rchart,
        (string)$r->info,
        (string)$r->review,
        (int)$r->is_show_url,
        (int)$r->same_parent
      );
    })->toArray();
  }

  public function save(AdDetail $adDetail): AdDetailId
  {
    $res = $this->db->replace( 
      $this->table, 
      $adDetail->entity()
    );
    return new AdDetailId($this->db->insert_id);
  }

  public function delete(AdDetailId $adDetailId): bool
  {
    $res = $this->db->delete( 
      $this->table, 
      array( 'id' => $adDetailId->getId() )
    );
    return $res;
  }

  public function deleteByAdId(AdId $adId): bool
  {
    $res = $this->db->delete( 
      $this->table, 
      array( 'ad_id' => $adId->getId() )
    );
    return $res;
  }
}