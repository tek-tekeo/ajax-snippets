<?php
namespace AjaxSnippets\Application\AppServices\Asp;

use AjaxSneppets\Domain\Models\Asp;
use AjaxSneppets\Domain\Models\AspId;
use AjaxSnippets\Domain\Services\AspService;
use AjaxSnippets\Infrastructure\Repository\IAspRepository;

class AspGetService implements IAspGetService
{
  private IAspRepository $aspRepository;

  public function __construct(
    IAspRepository $aspRepository
  )
  {
    $this->aspRepository = $aspRepository;
  }

  public function handle(int $id)
  {
    $aspId = new AspId($id);
    $asp = $this->aspRepository->AspFindById($aspId);
    
    if($asp == null){
      return null;
    }
    return new AspData($asp); //クライアントが直接ドメインオブジェクト　Asp()を操作できないように、DTOで対応する
  }

}

class AspData
{
  public function __construct(Asp $source)
  {
    $this->id = $source->getId();
    $this->aspName = $source->getAspName();
    $this->connectString = $source->getConnectString();
  }

  public function id(){
    return $this->id;
  }

  public function aspName(){
    return $this->aspName;
  }

  public function connectString(){
    return $this->connectString;
  }
}
