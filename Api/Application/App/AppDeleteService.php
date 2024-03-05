<?php
namespace AjaxSnippets\Api\Application\App;

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Services\AppService;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Application\App\IAppDeleteService;
use AjaxSnippets\Api\Application\App\AppDeleteCommand;

class AppDeleteService implements IAppDeleteService
{
  private IAppRepository $appRepository;
  private AppService $appService;

  public function __construct(IAppRepository $appRepository)
  {
    $this->appRepository = $appRepository;
    $this->appService = new AppService($appRepository);
  }

  public function handle(AppDeleteCommand $cmd):bool
  {
    $targetId = new AppId($cmd->getId());
    $app = $this->appRepository->findById($targetId);

    if($app == null){
      return false; //ユーザーはいなかったようだ
    }
    
    return $this->appRepository->delete($targetId);
  }
}