<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asps\Asp;
use AjaxSnippets\Api\Domain\Models\Asps\AspId;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspDeleteCommand;

class AspDeleteService implements IAspDeleteService
{
  private $aspRepository;

  public function __construct(IAspRepository $aspRepository)
  {
    $this->aspRepository = $aspRepository;
  }

  public function handle(AspDeleteCommand $command):bool
  {
    $targetId = new AspId($command->id);
    $asp = $this->aspRepository->AspFindById($targetId);

    if($asp == null){
      return false; //ユーザーはいなかったようだ
    }
    
    return $this->aspRepository->delete($asp);
  }
}