<?php
namespace AjaxSnippets\Application\AppServices\Asp;

use AjaxSneppets\Domain\Models\Asp;
use AjaxSneppets\Domain\Models\AspId;
use AjaxSnippets\Infrastructure\Repository\IAspRepository;
use AjaxSnippets\Application\AppServices\Asp\IAspDeleteService;
use AjaxSnippets\Application\AppServices\Asp\AspDeleteCommand;

class AspDeleteService implements IAspDeleteService
{
  private IAspRepository $aspRepository;

  public function __construct(IAspRepository $aspRepository)
  {
    $this->aspRepository = $aspRepository;
  }

  public function handle(AspDeleteCommand $command):bool
  {
    $targetId = new AspId($command->id);
    $asp = $this->aspRepository->AspFindById($targetId);

    if($asp == null){
      print_r('すでにユーザーはいません');
      return null;
    }
    
    return $this->aspRepository->delete($asp);
  }
}