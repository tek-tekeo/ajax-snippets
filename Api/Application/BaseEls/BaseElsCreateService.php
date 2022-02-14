<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\App;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;

class BaseElsCreateService implements IBaseElsCreateService
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

  public function handle(BaseElsCreateCommand $cmd) : bool
  {
      $insertId = $this->parentNodeRepository->saveParent($cmd->getParent());
      $cmd->getApp()->setAppId($insertId);
      $app = $this->appRepository->saveApp($cmd->getApp());
      if($insertId !== 0 && $app == true){
        return true;
      }
      return false;
  }
}