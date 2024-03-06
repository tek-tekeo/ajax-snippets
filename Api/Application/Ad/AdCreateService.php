<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Application\Ad\AdCreateCommand;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Services\AdService;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;

class AdCreateService implements IAdCreateService
{
  
  public function __construct(
    private IAdRepository $adRepository
  ){}
  
  public function handle(AdCreateCommand $cmd)
  {
    // 広告情報の保存
    $ad = new Ad(
      new AdId(),
      $cmd->getAdName(),
      $cmd->getAdAnken(),
      $cmd->getAdAffiLink(),
      $cmd->getAdSLink(),
      $cmd->getAdAspName(),
      $cmd->getAdAffiImg(),
      $cmd->getAdImgTag(),
      $cmd->getAdSImgTag(),
      $cmd->getAdAffiImgWidth(),
      $cmd->getAdAffiImgHeight(),
      new AppId($cmd->getAppId())
    );
    
    $adService = new AdService($this->adRepository);
    if($adService->exists($ad)){
      throw new \Exception('ad already exists', 500);
    }

    return $this->adRepository->save($ad);

    // 広告の商品リンクを保存する

    return new AdId(1);
  }
}