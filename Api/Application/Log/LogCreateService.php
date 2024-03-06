<?php
namespace AjaxSnippets\Api\Application\Log;

use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Application\Log\LogCreateCommand;
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Domain\Models\Log\LogId;

class LogCreateService implements ILogCreateService
{
  public function __construct(private ILogRepository $logRepository)
  {}

  public function handle(LogCreateCommand $cmd): LogId
  {
    $log = new Log(
      new LogId(),
      $cmd->getItemId(),
      date_i18n("Y-m-d"),
      date_i18n("H:i:s"),
      $cmd->getPlace(),
      (string)ip2long($_SERVER['REMOTE_ADDR']),
      $_SERVER['HTTP_REFERER'] ?? 'none'
    );
    return $this->logRepository->save($log);
  }
}