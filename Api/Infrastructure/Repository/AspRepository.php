<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;

class AspRepository implements IAspRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'asp';
  }

  public function save(Asp $asp) : AspId
  {
    $result = $this->db->replace(
      $this->table,
      $asp->entity()
    );
    return new AspId($this->db->insert_id);
  }

  public function get(AspId $aspId): Asp | bool
  {
    $row = $this->db->get_row(
      $this->db->prepare(
        "SELECT * FROM " . $this->table . " WHERE id = %d",
        $aspId->getId()
      )
    );
    if (!$row) {
      return false;
    }

    return new Asp(
      new AspId((int)$row->id),
      $row->asp_name,
      $row->connect_string
    );
  }

  public function delete(AspId $aspId) : bool
  {
    $result = $this->db->delete(
      $this->table,
      ['id' => $aspId->getId()]
    );
    return $result;
  }

  public function existsByName(string $aspName): bool
  {
    $row = $this->db->get_row(
      $this->db->prepare(
        "SELECT * FROM " . $this->table . " WHERE asp_name = %s",
        $aspName
      )
    );
    if (!$row) {
      return false;
    }
    return true;
  }

  public function getAll() : array
  {
    $res = $this->db->get_results("SELECT * FROM ".PLUGIN_DB_PREFIX."asp");
    $asps = array();
    if(!$res == null){
      foreach($res as $r){
        $asp = new Asp(
          new AspId($r->id),
          $r->asp_name,
          $r->connect_string
        );
        array_push($asps, $asp);
      }
      return $asps;
    }
    return array();
  }

  public function findById(AspId $aspId)
  {
    $id = $aspId->getId();
    $res = $this->db->get_row("SELECT * FROM ".PLUGIN_DB_PREFIX."asp WHERE id = ".$id);
    if(!$res == null){
      $asp = new Asp(
        new AspId($res->id),
        $res->asp_name,
        $res->connect_string
      );
      return $asp;
    }
    return null;
  }

  public function findByName(string $aspName): Asp
  {
    $res = $this->db->get_row("SELECT * FROM ".PLUGIN_DB_PREFIX."asp WHERE asp_name = '".$aspName."'");
    if(!$res == null){
      $asp = new Asp(
        new AspId($res->id),
        $res->asp_name,
        $res->connect_string
      );
      return $asp;
    }
    throw new \Exception('名前に該当するASPが存在しません。');
    return null;
  }
}