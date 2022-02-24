<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asps\Asp;
use AjaxSnippets\Api\Domain\Models\Asps\AspId;
use AjaxSnippets\Api\Domain\Services\AspService;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;
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

  public function handle(AspUpdateCommand $command){
    $targetId = new AspId($command->id);
    $asp = $this->aspRepository->AspFindById($targetId);
    if($asp == null){
      print_r('userは存在しません');
      return false;
    }
    //リクエストされた値を設定
    $asp->setAspName($command->aspName);
    $asp->setConnectString($command->connectString);
    
    //同一名の存在をチェック
    if($this->aspService->exist($asp)){ //ドメインサービスを使う
      return false; //同じ名前がおるので棄却
    }
    //書き込み
    return $this->aspRepository->save($asp);
  }
}