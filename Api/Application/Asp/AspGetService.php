<?php
namespace AjaxSnippets\Api\Application\Asp;

use AjaxSnippets\Api\Domain\Models\Asps\Asp;
use AjaxSnippets\Api\Domain\Models\Asps\AspId;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;

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
    $aspId = new AspId($cmd->id);
    $asp = $this->aspRepository->AspFindById($aspId);
    
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
