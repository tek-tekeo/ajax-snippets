<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Tags\Tag;
use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\TagLinks\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLinks\ITagLinkRepository;

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

  public function save(TagLink $tagLink) : bool
  {
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'id' => $tagLink->getId(),
        'item_id' => $tagLink->getItemId(),
        'tag_id' => $tagLink->getTagId() 
      ), 
      array( 
        '%d',
        '%d', 
        '%d' 
      )
    );
    return $res;
  }

  public function delete(int $itemId) : bool
  {
    $res = $this->db->delete(
      $this->table,
      array( 'item_id' => $itemId )
    );

    return $res;
  }

  public function get(int $itemId) : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table . " WHERE item_id=".$itemId);
    $tags = array();
    if(!$res == null){
      foreach($res as $r){
        $tag = new TagLink(
          $r->id,
          new Detail($r->item_id),
          new Tag($r->tag_id)
        );
        array_push($tags, $tag);
      }
      return $tags;
    }
    return array();
  }

  public function getItemIdsByTag(int $tagId) : array
  {
    $sql = "SELECT DISTINCT item_id FROM ".$this->table." where tag_id in (".$tagId.") group by item_id having count(*) >= ".count(explode(",", $tagId));
    $res = $this->db->get_results($sql);
    $array = array();
    foreach($res as $r){
      array_push($array, $r->item_id);
    }
    return $array;
  }
}
