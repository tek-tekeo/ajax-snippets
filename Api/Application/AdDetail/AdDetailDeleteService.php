<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteCommand;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailDeleteService;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;

class AdDetailDeleteService implements IAdDetailDeleteService
{
  public function __construct(
    private IAdDetailRepository $adDetailRepository,
    private ITagLinkRepository $tagLinkRepository
  ){}

  public function handle(AdDetailDeleteCommand $cmd): bool
  {
    $adDetailId = new AdDetailId($cmd->getId());
    $this->tagLinkRepository->delete($adDetailId);
    return $this->adDetailRepository->delete($adDetailId);
  }

  public function handleReview(AdDetailDeleteCommand $cmd): bool
  {
    $reviewId = $cmd->getId();
    return $this->adDetailRepository->deleteReview($reviewId);
  }
}