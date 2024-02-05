<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Application\DTO\AspData;

class AspGetService implements IAspGetService
{
  private $aspRepository;

  public function __construct(
    IAspRepository $aspRepository
  )
  {
    $this->aspRepository = $aspRepository;
  }

  public function handle(AspGetCommand $cmd)
  {
    $aspId = new AspId($cmd->getId());
    $asp = $this->aspRepository->get($aspId);
    
    if($asp == null){
      return null;
    }

    return new AspData($asp); //クライアントが直接ドメインオブジェクト　Asp()を操作できないように、DTOで対応する
  }

  public function getAll()
  {
    $asps = $this->aspRepository->getAll();
    $aspsData = array();
    foreach($asps as $asp){
      $aspData = new AspData($asp);
      array_push($aspsData, $aspData);
    }
    return $aspsData;
  }

}

