<?php
namespace AjaxSnippets\Api\Application\App;

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Application\DTO\AppData;

class AppGetService implements IAppGetService
{
  private $appRepository;

  public function __construct(
    IAppRepository $appRepository
  )
  {
    $this->appRepository = $appRepository;
  }
  
  public function getAll()
  {
    $apps = $this->appRepository->getAll();
    return collect($apps)->map(function($app){
      return new AppData($app);
    });
  }

  public function handle(AppGetCommand $cmd):  AppData
  {
    $appId = new AppId($cmd->getId());
    $app = $this->appRepository->findById($appId);
    
    if($app == null){
      return null;
    }

    return new AppData($app); //クライアントが直接ドメインオブジェクト　App()を操作できないように、DTOで対応する
  }

}

