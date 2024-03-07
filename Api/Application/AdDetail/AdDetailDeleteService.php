<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteCommand;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailDeleteService;

class AdDetailDeleteService implements IAdDetailDeleteService
{
  public function __construct(private IAdDetailRepository $adDetailRepository)
  {}

  public function handle(AdDetailDeleteCommand $cmd): bool
  {
    $adDetailId = new AdDetailId($cmd->getId());
    return $this->adDetailRepository->delete($adDetailId);
  }
}