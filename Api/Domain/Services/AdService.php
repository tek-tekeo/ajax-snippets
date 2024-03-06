<?php
namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;

class AdService
{

  public function __construct(
    private IAdRepository $adRepository
  ){}

  public function exists(Ad $ad): bool
  {
    return (bool)$this->adRepository->findByName($ad->getName());
  }
}