<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
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
	  $sql = "SELECT D.*, B.name as parent_name FROM ". PLUGIN_DB_PREFIX."base As B RIGHT JOIN ". PLUGIN_DB_PREFIX."detail As D ON B.id = D.base_id where D.item_name LIKE '%".$name."%' OR B.name LIKE '%".$name."%' order by B.name asc, D.item_name asc";
    $res = $this->db->get_results($sql);
    $details = array();
    if(!$res == null){
      foreach($res as $r){

        $parent = new ParentNode(
          (int)$r->base_id,
          (string)$r->parent_name
        );
        $detail = new Detail(
          (int)$r->id,
          $parent,
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
        array_push($details, $detail);
      }
      return $details;
    }
    return array();
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

  public function save(AdDetail $adDetail): AdDetailId
  {
    $res = $this->db->replace( 
      $this->table, 
      $adDetail->entity()
    );
    return new AdDetailId($this->db->insert_id);
  }
}