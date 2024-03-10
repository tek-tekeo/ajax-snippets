<?php
namespace AjaxSnippets\Api\Application\App;

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Application\DTO\AppData;

class AppGetService implements IAppGetService
{
  public function __construct(private IAppRepository $appRepository)
  {}
  
  public function getAll()
  {
    $apps = $this->appRepository->getAll();
    return collect($apps)->map(function($app){
      return new AppData($app);
    })->toArray();
  }

  public function handle(AppGetCommand $cmd):  AppData
  {
    $appId = new AppId($cmd->getId());
    $app = $this->appRepository->findById($appId);
    
    if($app == null){
      return null;
    }
    return new AppData($app);
  }

  public function findById(AppId $appId): AppData
  {
    $app = $this->appRepository->findById($appId);
    return new AppData($app);
  }

}

