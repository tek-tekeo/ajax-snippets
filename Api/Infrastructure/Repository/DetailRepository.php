<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use Exception;

class DetailRepository implements IDetailRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'detail';
  }

  public function DetailFindByName(string $name) : array
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

  public function DetailFindById(int $detailId): ?Detail
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table." WHERE id=".$detailId);
    if(!$res == null){				
      $detail = new Detail(
        (int)$res->id,
        new ParentNode((int)$res->base_id),
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
      return $detail;
    }
    throw new Exception('Detail IDに該当するデータが存在しません。');
    return null;
  }

  public function DetailFindLatest(){
    $res = $this->db->get_row("SELECT * FROM ".$this->table." ORDER BY id DESC LIMIT 1");
    if(!$res == null){				
      $detail = new Detail(
        (int)$res->id,
        new ParentNode((int)$res->base_id),
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
      return $detail;
    }
    throw new Exception('Detail IDに該当するデータが存在しません。');
    return null;
  }

  public function saveDetail(Detail $detail) : int
  {
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'id' => $detail->id(),
        'base_id' => $detail->parent()->id(),
        'item_name' => $detail->itemName(),
        'official_item_link' => $detail->officialItemLink(),
        'affi_item_link' => $detail->affiItemLink(),
        'detail_img' => $detail->detailImg(),
        'amazon_asin' => $detail->amazonAsin(),
        'rakuten_id' => $detail->rakutenId(),
        'rchart' => $detail->rchart(),
        'info' => $detail->info(),
        'review' => $detail->review(),
        'is_show_url' => $detail->isShowUrl(),
        'same_parent' => $detail->sameParent()
      ), 
      array( 
        '%d',
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
    return $this->db->insert_id;
  }
}