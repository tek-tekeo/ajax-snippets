<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Application\Ad\IAdGetService;
use AjaxSnippets\Api\Application\Ad\AdGetCommand;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Application\DTO\Ad\AdData;

class AdGetService implements IAdGetService
{
  public function __construct(private IAdRepository $adRepository)
  {}

  public function handle(AdGetCommand $cmd)
  {
    $adId = new AdId($cmd->getId());
    return new AdData($this->adRepository->findById($adId));
  }

  public function getAdsByName(string $name)
  {
    $res = $this->adRepository->findByName($name);
    return collect($res)->map(function($ad){
      return new AdData($ad);
    })->toArray();
  }

  public function getAll()
  {
    $res = $this->adRepository->findAll();
    return collect($res)->map(function($ad){
      return new AdData($ad);
    })->toArray();
  }
}