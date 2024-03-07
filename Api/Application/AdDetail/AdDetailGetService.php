<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\EditDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\AffiLinkData;

class AdDetailGetService implements IAdDetailGetService
{
  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository,
    private IAspRepository $aspRepository
  ){}

  public function handle(AdDetailGetCommand $cmd)
  {
    $detail = $this->adDetailRepository->findById(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($detail->getAdId());
    return new AdDetailData($ad, $detail);
  }

  public function getDetailsFindByName(string $name){
    $details = $this->adDetailRepository->findByName($name);

    return collect($details)->map(function($adDetail){
      $ad = $this->adRepository->findById($adDetail->getAdId());
      return new AdDetailData($ad, $adDetail);
    })->toArray();
  }

  public function getEditorAnkenList(string $name){
    $details = $this->adDetailRepository->findByName($name);
    return collect($details)->map(function($adDetail){
      $ad = $this->adRepository->findById($adDetail->getAdId());
      $asp = $this->aspRepository->findByName($ad->getAspName());
      return new EditDetailData($ad, $adDetail, $asp);
    })->toArray();

  }

  public function getLinkMaker(AdDetailGetCommand $cmd){
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($adDetail->getAdId());
    return new AffiLinkData($ad, $adDetail);
  }

  public function getLatestDetail(){
    $detail = $this->adDetailRepository->findLatest();
    $ad = $this->adRepository->findById($detail->getAdId());
    $asp = $this->aspRepository->findByName($ad->getAspName());
    return new AdDetailData($ad, $detail, $asp);
  }

}