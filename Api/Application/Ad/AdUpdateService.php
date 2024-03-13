<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Application\Ad\IAdUpdateService;
use AjaxSnippets\Api\Application\Ad\AdUpdateCommand;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;

class AdUpdateService implements IAdUpdateService
{
  public function __construct(private IAdRepository $adRepository)
  {}

  public function handle(AdUpdateCommand $cmd)
  {
    $adId = new AdId($cmd->getId());
    $ad = $this->adRepository->findById($adId);

    // 広告情報を更新する
    $updateAd = new Ad(
      $ad->getAdId(),
      ($cmd->getName()) ? $cmd->getName() : $ad->getName(),
      ($cmd->getAnken()) ? $cmd->getAnken() : $ad->getAnken(),
      ($cmd->getAffiLink()) ? $cmd->getAffiLink() : $ad->getAffiLink(),
      ($cmd->getSLink()) ? $cmd->getSLink() : $ad->getSLink(),
      ($cmd->getAspName()) ? $cmd->getAspName() : $ad->getAspName(),
      ($cmd->getAffiImg()) ? $cmd->getAffiImg() : $ad->getAffiImg(),
      ($cmd->getImgTag()) ? $cmd->getImgTag() : $ad->getImgTag(),
      ($cmd->getSImgTag()) ? $cmd->getSImgTag() : $ad->getSImgTag(),
      ($cmd->getAffiImgWidth()) ? $cmd->getAffiImgWidth() : $ad->getAffiImgWidth(),
      ($cmd->getAffiImgHeight()) ? $cmd->getAffiImgHeight() : $ad->getAffiImgHeight(),
      ($cmd->getAppId()) ? new AppId($cmd->getAppId()) : $ad->getAppId(),
    );

    return $this->adRepository->save($updateAd);
  }

}