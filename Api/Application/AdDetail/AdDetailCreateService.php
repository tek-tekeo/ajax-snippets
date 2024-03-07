<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateCommand;

class AdDetailCreateService implements IAdDetailCreateService
{
  public function __construct(
    private IAdDetailRepository $detailRepository
  ){}

  public function handle(AdDetailCreateCommand $cmd): AdDetailId
  {
    $adDetail = new AdDetail(
      new AdDetailId(),
      new AdId($cmd->getAdId()),
      $cmd->getItemName(),
      $cmd->getOfficialItemLink(),
      $cmd->getAffiItemLink(),
      $cmd->getDetailImg(),
      $cmd->getAmazonAsin(),
      $cmd->getRakutenId(),
      $cmd->getRchart(),
      $cmd->getInfo(),
      $cmd->getReview(),
      $cmd->getIsShowUrl(),
      $cmd->getSameParent()
    );
    return $this->detailRepository->save($adDetail);
  }
}