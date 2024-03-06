<?php
namespace AjaxSnippets\Api\Application\Log;

use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;

interface ILogDeleteService
{
  public function handle(LogDeleteCommand $cmd): bool;
}

class LogDeleteCommand
{
  private int $id;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = (int)$req->get_param('logId');
  }

  public function getId(): int
  {
    return $this->id;
  }

}