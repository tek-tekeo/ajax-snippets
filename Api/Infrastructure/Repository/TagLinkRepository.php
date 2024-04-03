<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;

class TagLinkRepository implements ITagLinkRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'tag_link';
  }

  public function findByName(string $name)
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table . " WHERE name='".$name."'");
    if(!$res == null){
      return new Tag(
        $res[0]->id,
        $res[0]->name
      );
    }
    return null;
  }

  public function save(TagLink $tagLink): TagLinkId
  {  
    $res = $this->db->replace( 
      $this->table, 
      $tagLink->entity()
    );
    return new TagLinkId($this->db->insert_id);
  }

  public function update(TagLink $tagLink) : TagLinkId
  {
    $res = $this->db->replace(
      $this->table,
      $tagLink->entity()
    );
    return new TagLinkId($this->db->insert_id);
  }

  public function delete(AdDetailId $adDetailId) : bool
  {
    $res = $this->db->delete(
      $this->table,
      array( 'ad_detail_id' => $adDetailId->getId() )
    );

    return $res;
  }

  public function findByAdDetailId(AdDetailId $adDetailId) : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table . " WHERE ad_detail_id=".$adDetailId->getId());
    return collect($res)->map(function($r){
      return new TagLink(
        new TagLinkId($r->id),
        new AdDetailId($r->ad_detail_id),
        new TagId($r->tag_id)
      );
    })->toArray();
  }

  public function getItemIdsByTag(TagId $tagId) : array
  {
    $sql = "SELECT DISTINCT ad_detail_id FROM ".$this->table." where tag_id in (".$tagId->getId().") group by ad_detail_id having count(*) >= ".count(explode(",", $tagId->getId()));
    $res = $this->db->get_results($sql);
    return collect($res)->map(function($r){
      return $r->ad_detail_id;
    })->toArray();

  }

  public function getAdDetailIdsByTagString(string $ids) : array
  {
    $sql = "SELECT DISTINCT ad_detail_id FROM ".$this->table." where tag_id in (".$ids.") group by ad_detail_id having count(*) >= ".count(explode(",", $ids));
    $res = $this->db->get_results($sql);
    return collect($res)->map(function($r){
      return $r->ad_detail_id;
    })->toArray();
  }
}
