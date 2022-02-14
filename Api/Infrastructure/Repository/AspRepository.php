<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Asps\AspId;
use AjaxSnippets\Api\Domain\Models\Asps\Asp;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;

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

  public function save(Asp $asp) : bool
  {
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'id' => $asp->getId(),
        'asp_name' => $asp->getAspName(),
        'connect_string' => $asp->getConnectString() 
      ), 
      array( 
        '%d',
        '%s', 
        '%s' 
      )
    );
    return $res;
  }

  public function delete(Asp $asp) : bool
  {
    $res = $this->db->delete(
      $this->table,
      array( 'id' => $asp->getId() )
    );

    return $res;
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

  public function AspFindById(AspId $aspId)
  {
    $id = $aspId->id;
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

  public function AspFindByName(Asp $asp)
  {
    $res = $this->db->get_row("SELECT * FROM ".PLUGIN_DB_PREFIX."asp WHERE asp_name = ".$asp->getAspName());
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
}