<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Tags\Tag;
use AjaxSnippets\Api\Domain\Models\Tags\ITagRepository;

class TagRepository implements ITagRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'tag';
  }

  public function save(Tag $tag) : bool
  {
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'id' => $tag->getId(),
        'tag_name' => $tag->getTagName(),
        'tag_order' => $tag->getTagOrder() 
      ), 
      array( 
        '%d',
        '%s', 
        '%s' 
      )
    );
    return $res;
  }

  public function delete(int $id) : bool
  {
    $res = $this->db->delete(
      $this->table,
      array( 'id' => $id )
    );

    return $res;
  }

  public function get(int $id)
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table . " WHERE id=".$id);
    return new Tag(
      $res->id,
      $res->tag_name,
      $res->tag_order
    );
  }

  public function getAllTags() : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table);
    $tags = array();
    if(!$res == null){
      foreach($res as $r){
        $tag = new Tag(
          $r->id,
          $r->tag_name,
          $r->tag_order
        );
        array_push($tags, $tag);
      }
      return $tags;
    }
    return array();
  }

  public function getTagsByName(string $name) : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table. " where tag_name LIKE '%".$name."%'");
    $tags = array();
    if(!$res == null){
      foreach($res as $r){
        $tag = new Tag(
          $r->id,
          $r->tag_name,
          $r->tag_order
        );
        array_push($tags, $tag);
      }
      return $tags;
    }
    return array();
  }

}