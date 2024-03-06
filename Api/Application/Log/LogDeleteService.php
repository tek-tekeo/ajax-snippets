<?php
namespace AjaxSnippets\Api\Application\Log;

use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Domain\Models\Log\LogId;

class LogDeleteService implements ILogDeleteService
{
  public function __construct(private ILogRepository $logRepository)
  {}

  public function handle(LogDeleteCommand $cmd): bool
  {
    $logId = new LogId($cmd->getId());
    return $this->logRepository->delete($logId);
  }
} 