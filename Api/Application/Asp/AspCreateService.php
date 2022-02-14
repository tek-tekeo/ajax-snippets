<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asps\Asp;
use AjaxSnippets\Api\Domain\Models\Asps\AspId;
use AjaxSnippets\Api\Domain\Services\AspService;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;
use AjaxSnippets\Api\Application\Asp\AspCreateCommand;

class AspCreateService implements IAspCreateService
{
  private $aspRepository;
  private $aspService;

  public function __construct(
    AspService $aspService,
    IAspRepository $aspRepository
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