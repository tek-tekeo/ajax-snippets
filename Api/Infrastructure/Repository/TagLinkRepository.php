<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

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
          $r->item_id,
          $r->tag_id
        );
        array_push($tags, $tag);
      }
      return $tags;
    }
    return array();
  }

  // public function getAllTags() : array
  // {
  //   $res = $this->db->get_results("SELECT * FROM ".$this->table);
  //   $tags = array();
  //   if(!$res == null){
  //     foreach($res as $r){
  //       $tag = new Tag(
  //         $r->id,
  //         $r->tag_name,
  //         $r->tag_order
  //       );
  //       array_push($tags, $tag);
  //     }
  //     return $tags;
  //   }
  //   return array();
  // }

}