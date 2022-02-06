<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\App;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;

class BaseElsUpdateService implements IBaseElsUpdateService
{
  private $parentNodeRepository;
  private $appRepository;

  public function __construct(
    IParentNodeRepository $parentNodeRepository,
    IAppRepository $appRepository
  )
  {
    $this->parentNodeRepository = $parentNodeRepository;
    $this->appRepository = $appRepository;
  }

  public function handle(BaseElsUpdateCommand $cmd) : bool
  {
      $parent = $this->parentNodeRepository->saveParent($cmd->parent);
      $app = $this->appRepository->saveApp($cmd->app);
      if($parent == true && $app == true){
        return true;
      }
      return false; //クライアントが直接ドメインオブジェクト　Asp()を操作できないように、DTOで対応する
  }
}