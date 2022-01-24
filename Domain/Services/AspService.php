<?php
namespace AjaxSnippets\Domain\Services;

use AjaxSneppets\Domain\Models\Asp;
use AjaxSnippets\Infrastructure\Repository\IAspRepository;

class AspService
{
  private IAspRepository $aspRepository;

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