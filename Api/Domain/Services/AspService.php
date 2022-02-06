<?php
namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\Asp;
use AjaxSnippets\Api\Domain\Models\IAspRepository;

class AspService
{
  private $aspRepository;

  public function __construct(IAspRepository $aspRepository)
  {
    $this->aspRepository = $aspRepository;
  }

  public function exist(Asp $asp) : bool
  {
    //名前で重複チェック
    $findAsp = $this->aspRepository->AspFindByName($asp);
    return isset($findAsp);
  }
}