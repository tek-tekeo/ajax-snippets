<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;

class DetailCreateService implements IDetailCreateService
{
  private $detailRepository;
  private $parentNodeRepository;

  public function __construct(IDetailRepository $detailRepository)
  {
    $this->detailRepository = $detailRepository;
  }

  public function handle(DetailCreateCommand $cmd):int
  {
    $res = $this->detailRepository->saveDetail($cmd->detail);
    return $res;
  }
}