<?php
namespace AjaxSnippets\Api\Domain\Models\Log;

use AjaxSnippets\Api\Domain\Models\Log\Log;

interface ILogRepository
{
  public function save(Log $log);
  public function getLogs(string $where = ''): array;
  // public function getArticleCountLogs(string $where = ''): array;
  public function getItemCountLogs(string $where = ''): array;
  public function getDayPerClick(string $where = ''): array;
}