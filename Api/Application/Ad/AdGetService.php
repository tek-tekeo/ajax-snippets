<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Application\Ad\IAdGetService;
use AjaxSnippets\Api\Application\Ad\AdGetCommand;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;

class AdGetService implements IAdGetService
{
  public function __construct(private IAdRepository $adRepository)
  {}

  public function handle(AdGetCommand $cmd)
  {
    $adId = new AdId($cmd->getId());
    return $this->adRepository->findById($adId);
  }

  public function getAdsByName(string $name)
  {
    return $this->adRepository->findByName($name);
  }

  public function getAll()
  {
    return $this->adRepository->findAll();
  }
}