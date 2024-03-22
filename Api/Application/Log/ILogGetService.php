<?php
namespace AjaxSnippets\Api\Application\Log;

use AjaxSnippets\Api\Domain\Models\Log\Log;

interface ILogGetService
{
  public function handle(LogGetCommand $cmd): array;
  public function getArticleCountLogs(LogGetCommand $cmd): array;
  public function getItemCountLogs(LogGetCommand $cmd): array;
  public function getDayPerClick(LogGetCommand $cmd): array;
}

class LogGetCommand
{
  private array $dates;
  private int $limit;

  public function __construct(\WP_REST_Request $req)
  {
    $this->dates = $req->get_param('dates');
    $this->limit = $req->get_param('limit');
  }

  public function getWhereSortByDate()
  {
    array_multisort( array_map( "strtotime", $this->dates ), SORT_ASC, $this->dates ) ;
    $sql = " where date between '".$this->dates[0]."' AND '".$this->dates[1]."' order by date desc, time desc limit ".$this->limit;
    return $sql;
  }

  public function getWhereSortByAnken()
  {
    array_multisort( array_map( "strtotime", $this->dates ), SORT_ASC, $this->dates ) ;
    $sql = " where date between '".$this->dates[0]."' AND '".$this->dates[1]."' group by item_id, place order by COUNT(*) desc";
    return $sql;
  }

  public function getWhereSortByArticle()
  {
    array_multisort( array_map( "strtotime", $this->dates ), SORT_ASC, $this->dates ) ;
    $sql = " where date between '".$this->dates[0]."' AND '".$this->dates[1]."' group by post_addr, place order by COUNT(*) desc";
    return $sql;
  }

  public function getWhereDayPerClick()
  {
    array_multisort( array_map( "strtotime", $this->dates ), SORT_ASC, $this->dates ) ;
    $sql = " where date between '".$this->dates[0]."' AND '".$this->dates[1]."' group by date order by COUNT(*) desc";
    return $sql;
  }

}