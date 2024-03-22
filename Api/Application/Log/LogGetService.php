<?php
namespace AjaxSnippets\Api\Application\Log;

use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Application\Log\ILogGetService;
use AjaxSnippets\Api\Application\Log\LogGetCommand;
use AjaxSnippets\Api\Infrastructure\Repository\LogRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailRepository;
use AjaxSnippets\Api\Domain\Services\AdService;
use AjaxSnippets\Api\Application\DTO\Log\LogData;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Application\DTO\Log\ClickData;
use AjaxSnippets\Api\Application\DTO\Log\DayPerClickData;
use stdClass;

class LogGetService implements ILogGetService
{
  private $adService;
  public function __construct(
    private ILogRepository $logRepository
  ){
    $this->adService = new AdService(new AdRepository(), new AdDetailRepository());
  }

  public function handle(LogGetCommand $cmd): array
  {
    $logs = $this->logRepository->getLogs($cmd->getWhereSortByDate());
    
    return collect($logs)->map(function($log){
      $name = $this->adService->getFullNameByAdDetailId($log->getAdDetailId());
      return new LogData($log, $name);
    })->toArray();
  }

  public function getArticleCountLogs(LogGetCommand $cmd): array
  {
    $res = $this->logRepository->getArticleCountLogs($cmd->getWhereSortByDate());
    return collect($res)->map(function($data) {
      return new ClickData($data->post_addr, $data->place, $data->clicks);
    })->toArray();
  }

  // 案件・場所
  public function getItemCountLogs(LogGetCommand $cmd): array
  {
    $res = $this->logRepository->getItemCountLogs($cmd->getWhereSortByDate());
    return collect($res)->map(function($data) {
      $name = $this->adService->getFullNameByAdDetailId(new AdDetailId($data->ad_detail_id));
      return new ClickData($name, $data->place, $data->clicks);
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