<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\Ad\IAdDeleteService;
use AjaxSnippets\Api\Application\Ad\AdDeleteCommand;

class AdDeleteService implements IAdDeleteService
{
  public function __construct(private IAdRepository $adRepository)
  {}

  public function handle(AdDeleteCommand $cmd): bool
  {
    $adId = new AdId($cmd->getId());
    return $this->adRepository->delete($adId);
  }
} 