<?php
namespace AjaxSnippets\Application\AppServices\Asp;

use AjaxSneppets\Domain\Models\Asp;
use AjaxSneppets\Domain\Models\AspId;
use AjaxSnippets\Domain\Services\AspService;
use AjaxSnippets\Infrastructure\Repository\IAspRepository;
use AjaxSnippets\Application\AppServices\Asp\AspCreateCommand;

class AspCreateService implements IAspCreateService
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

  public function handle(AspCreateCommand $cmd):bool
  {
    $asp = new Asp(
      new AspId(),
      $cmd->aspName,
      $cmd->connectString
    );

    if($this->aspService->exist($asp)){
      return false;
    }
    $this->aspRepository->save($asp);
    return true;
  }
}