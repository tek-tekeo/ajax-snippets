<?php
namespace AjaxSnippets\Application\AppServices\Asp;

use AjaxSneppets\Domain\Models\Asp;
use AjaxSneppets\Domain\Models\AspId;
use AjaxSnippets\Domain\Services\AspService;
use AjaxSnippets\Infrastructure\Repository\IAspRepository;
use AjaxSnippets\Application\AppServices\Asp\AspUpdateCommand;

class AspUpdateService implements IAspUpdateService
{
  private IAspRepository $aspRepository;
  private AspService $aspService;

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

    //同じ名前で単一にしたい場合
    if($this->aspService->exist($asp)){ //ドメインサービスを使う
      //名前の更新
      return false; //同じ名前がおるので棄却
    }
    $asp->setAspName($command->aspName);
    $asp->setConnectString($command->connectString);
    return $this->aspRepository->save($asp);
  }
}