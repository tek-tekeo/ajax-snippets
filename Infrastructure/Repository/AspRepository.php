<?php
namespace AjaxSnippets\Infrastructure\Repository;

use AjaxSneppets\Domain\Models\AspId;
use AjaxSneppets\Domain\Models\Asp;
use AjaxSnippets\Infrastructure\Repository\IAspRepository;

class AspRepository implements IAspRepository
{
  private $db;
  private $aspTable;
  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->aspTable = PLUGIN_DB_PREFIX.'asp';
  }

    public function save(Asp $asp) : bool
    {
      $res = $this->db->replace( 
        $this->aspTable, 
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
        $this->aspTable,
        array( 'id' => $asp->getId() )
      );

      return $res;
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