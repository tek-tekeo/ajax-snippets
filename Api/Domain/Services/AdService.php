<?php

namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;

class AdService
{

  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository
  ) {}

  public function exists(Ad $ad): bool
  {
    return (bool)$this->adRepository->findByName($ad->getName());
  }

  public function getFullNameByAdDetailId(AdDetailId $adDetailId): string
  {
    $adDetail = $this->adDetailRepository->findByIdWithDelete($adDetailId);
    $ad = $this->adRepository->findById($adDetail->getAdId());
    return $ad->getName() . ' ' . $adDetail->getItemName();
  }
}
