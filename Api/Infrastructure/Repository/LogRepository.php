<?php
namespace AjaxSnippets\Api\Infrastructure\Repository;

use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class LogRepository implements ILogRepository
{
  private $db;
  private $table;

  public function __construct()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX.'logs';
  }

  public function save(Log $log): LogId
  { 
    $res = $this->db->replace( 
      $this->table, 
      $log->entity()
    );
    return new LogId($this->db->insert_id);
  }

  public function delete(LogId $logId): bool
  {
    return $this->db->delete(
      $this->table,
      ['id' => $logId->getId()]
    );
  }

  public function getLogs(string $where = ''): array
  {
    $res = $this->db->get_results("SELECT * FROM ". $this->table.$where);
    return collect($res)->map(function($r){
      return new Log(
        new LogId($r->id),
        new AdDetailId($r->ad_detail_id),
        $r->date,
        $r->time,
        $r->place,
        $r->ip,
        $r->post_addr
      );
    })->toArray();
  }

  public function getItemCountLogs(string $where = ''): array
  {
    $res = $this->db->get_results("SELECT ad_detail_id, place, COUNT(*) as clicks FROM ". $this->table.$where);
    return collect($res)->toArray();
  }

  public function getArticleCountLogs(string $where = ''): array
  {
    $res = $this->db->get_results("SELECT post_addr, place, COUNT(*) as clicks FROM ". $this->table.$where);
    return collect($res)->toArray();
  }

  public function getDayPerClick(string $where = ''): array
  {
    $res = $this->db->get_results("SELECT date, count(DISTINCT ip) as clicks FROM ". $this->table.$where);
    return $res;
  }

}