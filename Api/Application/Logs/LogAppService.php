<?php
namespace AjaxSnippets\Api\Application\Logs;

use AjaxSnippets\Api\Domain\Models\Logs\Log;
use AjaxSnippets\Api\Domain\Models\Logs\ILogRepository;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;

class LogAppService
{
  private $logRepository;
  private $detailRepository;
  private $parentNodeRepository;

  public function __construct(
    ILogRepository $logRepository,
    IDetailRepository $detailRepository,
    IParentNodeRepository $parentNodeRepository
  )
  {
    $this->logRepository = $logRepository;
    $this->detailRepository = $detailRepository;
    $this->parentNodeRepository = $parentNodeRepository;
  }

  //クリックされた時のレコード処理
  public function record(LogRecordCommand $cmd) : void
  {
    try{
      $log = new Log(
        0,
        $cmd->getItemId(),
        date_i18n("Y-m-d"),
        date_i18n("H:i:s"),
        $cmd->getPlace(),
        (string)ip2long($_SERVER['REMOTE_ADDR']),
        $_SERVER['HTTP_REFERER']
      );
      $res = $this->logRepository->record($log);
    }catch(\Exception $e){
      //リファラがない場合はログの記録をしない
    }

  }

  public function getAllLogs($cmd) : array
  {
    $res = $this->logRepository->getLogs($cmd->getWhereSortByDate());
    
    $newLogData = array_map(function($r){

      return new LogData($r, $this->detailRepository, $this->parentNodeRepository);
    },$res);
    return $newLogData;
  }

  public function getAnkenLogs($cmd) : array
  {
    $res = $this->logRepository->getItemCountLogs($cmd->getWhereSortByAnken());
    $clickData = array_map(function($r){

      $detail = $this->detailRepository->DetailFindById($r->item_id);
      $parent = $this->parentNodeRepository->ParentFindById($detail->parent()->id());
      return new ClickData(
        (string)($parent->name() .' '. $detail->itemName()),
        (string)$r->place,
        (int)$r->clicks
      );
    },$res);
    return $clickData;
  }

  public function getArticleLogs($cmd) : array
  {
    $res = $this->logRepository->getArticleCountLogs($cmd->getWhereSortByArticle());
    $clickData = array_map(function($r){
      return new ClickData(
        (string)$r->post_addr,
        (string)$r->place,
        (int)$r->clicks
      );
    },$res);
    return $clickData;
  }

  public function getDayPerClick($cmd) : array
  {
    $res = $this->logRepository->getDayPerClick($cmd->getWhereDayPerClick());
    $clickData = array_map(function($r){
      return new DayPerClickData(
        (string)$r->date,
        (int)$r->clicks
      );
    },$res);
    return $clickData;
  }

}

class LogData
{
  private $detailRepository;
  private $parentNodeRepository;
  private $log;

  public function __construct(
    Log $log,
    IDetailRepository $detailRepository,
    IParentNodeRepository $parentNodeRepository
  )
  {
    $this->log = $log;
    $this->detailRepository = $detailRepository;
    $this->parentNodeRepository = $parentNodeRepository;
    $this->id = $log->getId();
    $this->itemId = $log->getItemId();
    $this->dateTime = $log->getDate().' '.$log->getTime();
    $this->place = $log->getPlace();
    $this->ip = $log->getIp();
    $this->postAddr = $log->getPostAddr();
    $this->name = $this->getAllName();
  }

  public function getAllName()
  {
    $detail = $this->detailRepository->DetailFindById($this->log->getItemId());
    $parent = $this->parentNodeRepository->ParentFindById($detail->parent()->id());
    
    return $parent->name() .' '. $detail->itemName();
  }
}

class ClickData
{
  public function __construct(
    string $key,
    string $place,
    int $clicks
  )
  {
    $this->key = $key;
    $this->place = $place;
    $this->clicks = $clicks;
  }
}

class DayPerClickData
{
  public function __construct(
    string $date,
    int $clicks
  )
  {
    $this->date = $date;
    $this->clicks = $clicks;
  }
}

class LogRecordCommand
{
  public function __construct(string $itemId, string $place)
  {
    $this->itemId = $itemId;
    $this->place = $place;
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function getPlace()
  {
    return $this->place;
  }
}

class LogGetCommand
{
  public function __construct(?array $dates, int $limit)
  {
    $this->dates = $dates;
    $this->limit = $limit;
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