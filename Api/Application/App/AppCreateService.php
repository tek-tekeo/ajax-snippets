<?php
namespace AjaxSnippets\Api\Application\App;

use AjaxSnippets\Api\Application\App\AppCreateCommand;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Services\AppService;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;

class AppCreateService implements IAppCreateService
{
  
  public function __construct(
    private IAppRepository $appRepository
  ){}
  
  public function handle(AppCreateCommand $cmd)
  {

    // 広告情報の保存
    $app = new App(
      new AppId(),
      $cmd->getName(),
      $cmd->getImg(),
      $cmd->getDev(),
      $cmd->getIosLink(),
      $cmd->getAndroidLink(),
      $cmd->getWebLink(),
      $cmd->getIosAffiLink(),
      $cmd->getAndroidAffiLink(),
      $cmd->getWebAffiLink(),
      $cmd->getArticle(),
      $cmd->getAppOrder(),
      $cmd->getAppPrice()
    );

    $appService = new AppService($this->appRepository);
    if($appService->exists($app)){
      throw new \Exception('app alreappy exists', 500);
    }
    return $this->appRepository->save($app);

  }
}