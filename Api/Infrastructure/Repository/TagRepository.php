<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;

class TagRepository implements ITagRepository 
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'tags';
  }

  public function save(Tag $tag) : TagId
  {
    $res = $this->db->replace( 
      $this->table, 
      $tag->entity()
    );
    return new TagId($this->db->insert_id);
  }

  public function findById(TagId $id)
  {
    $row = $this->db->get_row("SELECT * FROM ".$this->table." WHERE id = ".$id->getId());
    if(!$row == null){
      $tag = new Tag(
        new TagId($row->id),
        $row->tag_name,
        $row->tag_order
      );
      return $tag;
    }
    throw new \Exception('idに該当するタグがありません。');
    return null;
  }

  public function findByName(string $name = '') : array
  {
    $res = $this->db->get_results("SELECT * FROM " . $this->table . " where tag_name LIKE '%".$name."%'");
    $tags = array();
    if(!$res == null){
      foreach($res as $r){
        $tag = new Tag(
          new TagId($r->id),
          $r->tag_name,
          $r->tag_order
        );
        array_push($tags, $tag);
      }
      return $tags;
    }
    return array();
  }

  public function delete(tagId $tagId) : bool
  {
    $res = $this->db->delete(
      $this->table,
      array( 'id' => $tagId->getId() )
    );

    return $res;
  }
}