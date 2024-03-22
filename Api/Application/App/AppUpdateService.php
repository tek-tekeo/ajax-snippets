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
      return false;
    }
    //リクエストされた値を設定
    $updateApp = new App(
      $targetId,
      $cmd->getName() ?? $app->getName(),
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
    $appService = new AppService($this->appRepository);
    if($appService->exists($updateApp)){
      throw new \Exception('app name alreappy exists', 500);
    }
    //書き込み
    return $this->appRepository->save($updateApp);
  }
}