<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;


interface IAdUpdateService
{
  public function handle(AdUpdateCommand $cmd);
}

class AdUpdateCommand
{
  private int $id;
  private string $name;
  private string $anken;
  private float $affiLink;
  private string $sLink;
  private string $aspName;
  private string $affiImg;
  private string $imgTag;
  private string $sImgTag;
  private int $affiImgWidth;
  private int $affiImgHeight;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = (int)$req->get_param('id');
    $this->name = (string)$req->get_param('name');
    $this->anken = (string)$req->get_param('anken');
    $this->affiLink = (float)$req->get_param('affiLink');
    $this->sLink = (string)$req->get_param('sLink');
    $this->aspName = (string)$req->get_param('aspName');
    $this->affiImg = (string)$req->get_param('affiImg');
    $this->imgTag = (string)$req->get_param('imgTag');
    $this->sImgTag = (string)$req->get_param('sImgTag');
    $this->affiImgWidth = (int)$req->get_param('affiImgWidth');
    $this->affiImgHeight = (int)$req->get_param('affiImgHeight');
  }
  
  public function getId(): int
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getAnken(): string | null
  {
    return $this->anken;
  }

  public function getAffiLink(): float
  {
    return $this->affiLink;
  }

  public function getSLink(): string
  {
    return $this->sLink;
  }

  public function getAspName(): string
  {
    return $this->aspName;
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

}