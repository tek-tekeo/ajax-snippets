<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;

class AdDetailUpdateService implements IAdDetailUpdateService
{

  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository
  ){}

  public function handle(AdDetailUpdateCommand $cmd) : AdDetailId
  {
    $adDetailId = new AdDetailId($cmd->getId());
    $adDetail = $this->adDetailRepository->findById($adDetailId);

    $updateAdDetail = new AdDetail(
      $adDetailId,
      ($cmd->getAdId()) ? $cmd->getAdId() : $adDetail->getAdId(),
      ($cmd->getItemName()) ? $cmd->getItemName() : $adDetail->getItemName(),
      ($cmd->getOfficialItemLink()) ? $cmd->getOfficialItemLink() : $adDetail->getOfficialItemLink(),
      ($cmd->getAffiItemLink()) ? $cmd->getAffiItemLink() : $adDetail->getAffiItemLink(),
      ($cmd->getDetailImg()) ? $cmd->getDetailImg() : $adDetail->getDetailImg(),
      ($cmd->getAmazonAsin()) ? $cmd->getAmazonAsin() : $adDetail->getAmazonAsin(),
      ($cmd->getRakutenId()) ? $cmd->getRakutenId() : $adDetail->getRakutenId(),
      ($cmd->getRchart()) ? $cmd->getRchart() : $adDetail->getRchart(),
      ($cmd->getInfo()) ? $cmd->getInfo() : $adDetail->getInfo(),
      ($cmd->getReview()) ? $cmd->getReview() : $adDetail->getReview(),
      ($cmd->getIsShowUrl()) ? $cmd->getIsShowUrl() : $adDetail->getIsShowUrl(),
      ($cmd->getSameParent()) ? $cmd->getSameParent() : $adDetail->getSameParent()
    );
    return $this->adDetailRepository->save($updateAdDetail);
  }
}