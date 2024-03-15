<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Services\AspService;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspDeleteCommand;

class AspDeleteService implements IAspDeleteService
{
  private IAspRepository $aspRepository;
  private AspService $aspService;

  public function __construct(IAspRepository $aspRepository)
  {
    $this->aspRepository = $aspRepository;
    $this->aspService = new AspService($aspRepository);
  }

  public function handle(AspDeleteCommand $cmd):bool
  {
    $targetId = new AspId($cmd->getId());
    $asp = $this->aspRepository->findById($targetId);

    if($asp == null){
      return false; //ユーザーはいなかったようだ
    }
    
    return $this->aspRepository->delete($targetId);
  }
}