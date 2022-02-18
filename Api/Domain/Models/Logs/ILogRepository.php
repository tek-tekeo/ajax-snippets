<?php
namespace AjaxSnippets\Api\Domain\Models\Logs;

use AjaxSnippets\Api\Domain\Models\Logs\Log;

interface ILogRepository
{
  public function record(Log $log);
  public function getLogs(?string $where);
  public function getArticleCountLogs(?string $where);
  public function getItemCountLogs(?string $where);
  public function getDayPerClick(?string $where);
}