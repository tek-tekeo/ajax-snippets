<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;

class TagLinkGetService implements ITagLinkGetService
{
  public function __construct(
    private ITagLinkRepository $tagLinkRepository,
    private IAdDetailRepository $adDetailRepository,
    private IAdDetailChartRepository $adDetailChartRepository
  ){}

  public function handle(TagLinkGetCommand $cmd): array
  {
    return [];
    
  }

  public function getTagRanking(string $ids)
  {
    $adDetailIds = $this->tagLinkRepository->getAdDetailIdsByTagString($ids);
  
    return collect($adDetailIds)->map(function($id){
      $adDetailId = new AdDetailId($id);
      $adDetail = $this->adDetailRepository->findById($adDetailId);
      $tagLinks = $this->adDetailChartRepository->findByAdDetailId($adDetailId);
      return [
        'adDetailId' => $adDetailId->getId(),
        'name' => $adDetail->getItemName(),
        'rate' => round(
                    collect($tagLinks)->map(function($tagLink){
                      return $tagLink->getRate();
                    })->avg()
                  ,3)
        ];
    })->sortByDesc('rate')->values()->all();
  }
}