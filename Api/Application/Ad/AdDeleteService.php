<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\Ad\IAdDeleteService;
use AjaxSnippets\Api\Application\Ad\AdDeleteCommand;

class AdDeleteService implements IAdDeleteService
{
  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository
  ){}

  public function handle(AdDeleteCommand $cmd): bool
  {
    try{
      $adId = new AdId($cmd->getId());
      $this->adRepository->delete($adId); // 親要素の削除
      $this->adDetailRepository->deleteByAdId($adId); // 追加で子要素を削除する
      return true;
    }catch(\Exception $e){
      return false;
    }
    
  }
} 