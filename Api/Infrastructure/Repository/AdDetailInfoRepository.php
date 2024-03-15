<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class AdDetailInfoRepository implements IAdDetailInfoRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'ad_details_info';
  }

  public function save(AdDetailInfo $adDetailInfo): int
  {
    $res = $this->db->replace( 
      $this->table, 
      $adDetailInfo->entity()
    );
    return $this->db->insert_id;
  }

  public function findByAdDetailId(AdDetailId $adDetailId): array
  {
    $res = $this->db->get_results("SELECT * FROM ". $this->table." WHERE ad_detail_id = ".$adDetailId->getId(). " ORDER BY sort_order ASC");
    return collect($res)->map(function($r){
      return new AdDetailInfo(
        $r->id,
        new AdDetailId($r->ad_detail_id),
        $r->title,
        $r->content,
        $r->sort_order
      );
    })->toArray();
  }

  public function deleteByAdDetailId(AdDetailId $adDetailId): bool
  {
    $res = $this->db->delete( 
      $this->table, 
      array( 'ad_detail_id' => $adDetailId->getId() )
    );
    return $res;
  }
}