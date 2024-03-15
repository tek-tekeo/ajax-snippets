<?php
namespace AjaxSnippets\Api\Domain\Models\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

class Ad
{
  public function __construct(
    private AdId $adId,
    private string $name,
    private string $anken,
    private string $affiLink,
    private string $sLink,
    private AspId $aspId,
    private string $affiImg,
    private string $imgTag,
    private string $sImgTag,
    private int $affiImgWidth,
    private int $affiImgHeight,
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

  public function getSLink(): string
  {
    return $this->sLink;
  }

  public function getAspId(): AspId
  {
    return $this->aspId;
  }

  public function getAffiImg(): string
  {
    return $this->affiImg;
  }

  public function getImgTag(): string
  {
    return $this->imgTag;
  }

  public function getSImgTag(): string
  {
    return $this->sImgTag;
  }

  public function getAffiImgWidth(): int
  {
    return $this->affiImgWidth;
  }

  public function getAffiImgHeight(): int
  {
    return $this->affiImgHeight;
  }

  public function getAppId(): AppId
  {
    return $this->appId;
  }

  public function entity(): array
  {
    return [
      'id' => $this->adId->getId(),
      'name' => $this->name,
      'anken' => $this->anken,
      'affi_link' => $this->affiLink,
      's_link' => $this->sLink,
      'asp_id' => $this->aspId->getId(),
      'affi_img' => $this->affiImg,
      'img_tag' => $this->imgTag,
      's_img_tag' => $this->sImgTag,
      'affi_img_width' => $this->affiImgWidth,
      'affi_img_height' => $this->affiImgHeight,
      'app_id' => $this->appId->getId()
    ];
  }
}