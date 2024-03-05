<?php
namespace AjaxSnippets\Api\Application\App;

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Services\AppService;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Application\App\AppUpdateCommand;

class AppUpdateService implements IAppUpdateService
{
  private $appRepository;
  private $appService;

  public function __construct(
    IAppRepository $appRepository,
    AppService $appService
  )
  {
    $this->appRepository = $appRepository;
    $this->appService = $appService;
  }

  public function handle(AppUpdateCommand $cmd){
    $targetId = new AppId($cmd->getId());
    $app = $this->appRepository->findById($targetId);
    if($app == null){
      print_r('userは存在しません');
      return false;
    }
    //リクエストされた値を設定
    $updateApp = new App(
      $targetId,
      $cmd->getImg() ?? $app->getImg(),
      $cmd->getDev() ?? $app->getDev(),
      $cmd->getIosLink() ?? $app->getIosLink(),
      $cmd->getAndroidLink() ?? $app->getAndroidLink(),
      $cmd->getWebLink() ?? $app->getWebLink(),
      $cmd->getIosAffiLink() ?? $app->getIosAffiLink(),
      $cmd->getAndroidAffiLink() ?? $app->getAndroidAffiLink(),
      $cmd->getWebAffiLink() ?? $app->getWebAffiLink(),
      $cmd->getArticle() ?? $app->getArticle(),
      $cmd->getAppOrder() ?? $app->getAppOrder(),
      $cmd->getAppPrice() ?? $app->getAppPrice()
    );
    
    //同一名の存在をチェック
    if($this->appService->exists($updateApp)){ //ドメインサービスを使う
      return false; //同じ名前がおるので棄却
    }
    //書き込み
    return $this->appRepository->save($updateApp);
  }
}