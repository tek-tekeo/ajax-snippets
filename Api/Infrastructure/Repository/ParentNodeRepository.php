<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;

class ParentNodeRepository implements IParentNodeRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'base';
  }

  public function index(){
    return 'ok';
  }

  public function ParentFindById(int $parentId) : ParentNode
  {
    $res = $this->db->get_row("SELECT * FROM ".$this->table." WHERE id = ".$parentId);
    if(!$res == null){
      $parent = new ParentNode(
        $res->id,
        $res->name,
        $res->anken,
        $res->affi_link,
        $res->s_link,
        $res->asp_name,
        $res->affi_img,
        $res->img_tag,
        $res->s_img_tag
      );
      return $parent;
    }
    return null;
  }

  public function getAllParent() : array
  {
    $res = $this->db->get_results("SELECT * FROM ".$this->table);
    $parents = array();
    if(!$res == null){
      foreach($res as $r){
        $parent = new ParentNode(
          $r->id,
          $r->name,
          $r->anken,
          $r->affi_link,
          $r->s_link,
          $r->asp_name,
          $r->affi_img,
          $r->img_tag,
          $r->s_img_tag
        );
        array_push($parents, $parent);
      }
      return $parents;
    }
    return array();
  }

  public function saveParent(ParentNode $parent) : bool
  {
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'id' => $parent->id(),
        'name' => $parent->name(),
        'anken' => $parent->anken(),
        'affi_link' => $parent->affiLink(),
        's_link' => $parent->sLink(),
        'asp_name' => $parent->aspName(),
        'affi_img' => $parent->affiImg(),
        'img_tag' => $parent->imgTag(),
        's_img_tag' => $parent->sImgTag(),
      ), 
      array( 
        '%d',
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s' 
      )
    );
    return $res;
  }
}