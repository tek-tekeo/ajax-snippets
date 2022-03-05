<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\App;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;
use AjaxSnippets\Api\Application\Details\IDetailCreateService;
use AjaxSnippets\Api\Application\Details\DetailCreateCommand;
use AjaxSnippets\Api\Domain\Models\Details\Detail;

class BaseElsCreateService implements IBaseElsCreateService
{
  private $parentNodeRepository;
  private $appRepository;
  private $detailCreateService;

  public function __construct(
    IParentNodeRepository $parentNodeRepository,
    IAppRepository $appRepository,
    IDetailCreateService $detailCreateService
  )
  {
    $this->parentNodeRepository = $parentNodeRepository;
    $this->appRepository = $appRepository;
    $this->detailCreateService = $detailCreateService;
  }

  public function handle(BaseElsCreateCommand $cmd) : bool
  {
      $insertId = $this->parentNodeRepository->saveParent($cmd->getParent());
      $cmd->getApp()->setAppId($insertId);
      $app = $this->appRepository->saveApp($cmd->getApp());

      $detailCmd = new DetailCreateCommand(
        new Detail(
          0,
          new ParentNode((int)$insertId),
          $cmd->getParent()->name(),
          $cmd->getHomeUrl()
        )
      );
      $deatilInsertId = $this->detailCreateService->handle($detailCmd);

      if($insertId !== 0 && $app == true && $deatilInsertId !== 0){
        return true;
      }
      return false;
  }
}