<?php
namespace AjaxSnippets\Api\Domain\Models\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;

interface IAdRepository
{
  public function save(Ad $ad) : AdId;
  public function findById(AdId $adId) : Ad;
  public function findByName(string $name): array;
  public function findAll(): array;
}