<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Services\AspService;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Application\Asp\AspCreateCommand;

class AspCreateService implements IAspCreateService
{
  public function __construct(
    private AspService $aspService,
    private IAspRepository $aspRepository
  ){}

  public function handle(AspCreateCommand $cmd) : AspId
  {
    $asp = new Asp(
      new AspId(),
      $cmd->getAspName(),
      $cmd->getConnectString()
    );

    if($this->aspService->exists($asp)){
      throw new \Exception('asp already exists', 500);
    }
    return $this->aspRepository->save($asp);
  }
}