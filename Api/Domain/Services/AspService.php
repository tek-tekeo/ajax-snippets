<?php
namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;

class AspService
{

  public function __construct(
    private IAspRepository $aspRepository
  ){}

  public function exists(Asp $asp): bool
  {
    return $this->aspRepository->existsByName($asp->getAspName());
  }

  // public function exist(Asp $asp) : bool
  // {
  //   //名前で重複チェック
  //   try{
  //     $findAsp = $this->aspRepository->AspFindByName($asp->getAspName());
  //     return isset($findAsp);
  //   }catch (\Exception $e){
  //     return false;
  //   }
  // }
}