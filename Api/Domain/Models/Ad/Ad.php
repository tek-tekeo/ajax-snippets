<?php
namespace AjaxSnippets\Api\Domain\Models\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\App\AppId;

class Ad
{
  public function __construct(
    private AdId $adId,
    private string $name,
    private string $anken,
    private string $affiLink,
    private string $sLink,
    private string $aspName,
    private string $affiImage,
    private string $imageTag,
    private string $affiImageTag,
    private int $affiImageWidth,
    private int $affiImageHeight,
    private AppId $appId
  ){}

  public function getAdId(): AdId
  {
    return $this->adId;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getAnken(): string
  {
    return $this->anken;
  }

  public function getAffiLink(): string
  {
    return $this->affiLink;
  }

  public function entity(): array
  {
    return [
      'id' => $this->adId->getId(),
      'name' => $this->name,
      'anken' => $this->anken,
      'affi_link' => $this->affiLink,
      's_link' => $this->sLink,
      'asp_name' => $this->aspName,
      'affi_img' => $this->affiImage,
      'img_tag' => $this->imageTag,
      's_img_tag' => $this->affiImageTag,
      'affi_img_width' => $this->affiImageWidth,
      'affi_img_height' => $this->affiImageHeight,
      'app_id' => $this->appId->getId()
    ];
  }
}