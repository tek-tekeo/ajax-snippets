<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Logs\Log;
use AjaxSnippets\Api\Domain\Models\Logs\ILogRepository;

class LogRepository implements ILogRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'log';
  }

  public function record(Log $log):int
  { 
    $res = $this->db->replace( 
      $this->table, 
      array( 
        'item_id' => $log->getItemId(),
        'date' => $log->getDate(),
        'time' => $log->getTime(),
        'post_addr' => $log->getPostAddr(),
        'place' => $log->getPlace(),
        'ip' => $log->getIp(),
      ), 
      array( 
        '%d',
        '%s', 
        '%s', 
        '%s', 
        '%s', 
        '%s' 
      )
    );
    return $this->db->insert_id;
  }

  public function getLogs(?string $where):array
  {
    $res = $this->db->get_results("SELECT * FROM ". $this->table.$where);
    $logs = array();
    $logs = array_map(function($r){
      return new Log(
        $r->id,
        $r->item_id,
        $r->date,
        $r->time,
        $r->place,
        $r->ip,
        $r->post_addr
      );
    },$res);
    return $logs;
  }

  public function getItemCountLogs(?string $where):array
  {
    $res = $this->db->get_results("SELECT item_id, place, COUNT(*) as clicks FROM ". $this->table.$where);
    return $res;
  }

  public function getArticleCountLogs(?string $where):array
  {
    $res = $this->db->get_results("SELECT post_addr, place, COUNT(*) as clicks FROM ". $this->table.$where);
    return $res;
  }

  public function getDayPerClick(?string $where):array
  {
    $res = $this->db->get_results("SELECT date, count(DISTINCT ip) as clicks FROM ". $this->table.$where);
    return $res;
  }

}