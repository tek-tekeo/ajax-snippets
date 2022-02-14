<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;

class DetailUpdateService implements IDetailUpdateService
{
  private $parentNodeRepository;
  private $detailRepository;

  public function __construct(
    IParentNodeRepository $parentNodeRepository,
    IDetailRepository $detailRepository
  )
  {
    $this->parentNodeRepository = $parentNodeRepository;
    $this->detailRepository = $detailRepository;
  }

  public function handle(DetailUpdateCommand $cmd) : bool
  {
    $detail = $this->detailRepository->saveDetail($cmd->detail);
    return $detail;
  }
}