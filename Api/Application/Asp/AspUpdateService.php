<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Services\AspService;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Application\Asp\AspUpdateCommand;

class AspUpdateService implements IAspUpdateService
{
  private $aspRepository;
  private $aspService;

  public function __construct(
    IAspRepository $aspRepository,
    AspService $aspService
  )
  {
    $this->aspRepository = $aspRepository;
    $this->aspService = $aspService;
  }

  public function handle(AspUpdateCommand $cmd){
    $targetId = new AspId($cmd->getId());
    $asp = $this->aspRepository->get($targetId);
    if($asp == null){
      print_r('userは存在しません');
      return false;
    }
    //リクエストされた値を設定
    $asp->changeAspName($cmd->getAspName());
    $asp->changeConnectString($cmd->getConnectString());
    
    //同一名の存在をチェック
    if($this->aspService->exists($asp)){ //ドメインサービスを使う
      return false; //同じ名前がおるので棄却
    }
    //書き込み
    return $this->aspRepository->save($asp);
  }
}