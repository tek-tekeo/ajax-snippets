<?php
namespace AjaxSnippets\Api\Application\Log;

use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Application\Log\ILogGetService;
use AjaxSnippets\Api\Application\Log\LogCreateCommand;
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Application\DTO\Log\LogData;
use AjaxSnippets\Api\Application\DTO\Log\ClickData;
use AjaxSnippets\Api\Application\DTO\Log\DayPerClickData;
use stdClass;

class LogGetService implements ILogGetService
{
  public function __construct(private ILogRepository $logRepository)
  {}

  public function handle(LogGetCommand $cmd): array
  {
    $logs = $this->logRepository->getLogs($cmd->getWhereSortByDate());
    return collect($logs)->map(function($log) {
      return new LogData($log);
    })->toArray();
  }

  public function getArticleCountLogs(LogGetCommand $cmd): array
  {
    $res = $this->logRepository->getArticleCountLogs($cmd->getWhereSortByDate());
    return collect($res)->map(function($data) {
      return new ClickData($data->post_addr, $data->place, $data->clicks);
    })->toArray();
  }

  public function getItemCountLogs(LogGetCommand $cmd): array
  {
    $res = $this->logRepository->getItemCountLogs($cmd->getWhereSortByDate());
    return collect($res)->map(function($data) {
      $d = new stdClass();
      $d->post_addr = 'post_addr'; // なぜか名前を入れる
      $d->place = $data->place;
      $d->clicks = $data->clicks;
      // $adDetail = $this->adDetailRepository->findById($r->item_id);
      // $parent = $this->adRepository->ParentFindById($adDetail->parent()->id());
      return new ClickData('ad name', $d->place, $d->clicks);
    })->toArray();
  }

  public function getDayPerClick(LogGetCommand $cmd): array
  {
    $res = $this->logRepository->getDayPerClick($cmd->getWhereDayPerClick());
    return collect($res)->map(function($data) {
      return new DayPerClickData($data);
    })->toArray();
  }

}