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
}