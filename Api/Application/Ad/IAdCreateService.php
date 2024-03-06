<?php
namespace AjaxSnippets\Api\Application\Ad;

use AjaxSnippets\Api\Application\App\AppId;

interface IAdCreateService
{
  public function handle(AdCreateCommand $cmd);
}

class AdCreateCommand
{
  private string $name;
  private string $anken;
  private string $affiLink;
  private string $sLink;
  private string $aspName;
  private string $affiImg;
  private string $imgTag;
  private string $sImgTag;
  private string $affiImgWidth;
  private string $affiImgHeight;
  private int $appId;
  
  private string $homeUrl;

  public function __construct(\WP_REST_Request $req, $appId)
  {
    $this->name = (string)$req->get_param('name');
    $this->anken = (string)$req->get_param('anken');
    $this->affiLink = (string)$req->get_param('affiLink');
    $this->sLink = (string)$req->get_param('sLink');
    $this->aspName = (string)$req->get_param('aspName');
    $this->affiImg = (string)$req->get_param('affiImg');
    $this->imgTag = (string)$req->get_param('imgTag');
    $this->sImgTag = (string)$req->get_param('sImgTag');
    $this->affiImgWidth = (string)$req->get_param('affiImgWidth');
    $this->affiImgHeight = (string)$req->get_param('affiImgHeight');

    $this->appId = $appId->getId();
    
    $this->homeUrl = (string)$req->get_param('homeUrl');
  }

  public function getAdName()
  {
    return $this->name;
  }
  public function getAdAnken()
  {
    return $this->anken;
  }
  public function getAdAffiLink()
  {
    return $this->affiLink;
  }
  public function getAdSLink()
  {
    return $this->sLink;
  }
  public function getAdAspName()
  {
    return $this->aspName;
  }
  public function getAdAffiImg()
  {
    return $this->affiImg;
  }
  public function getAdImgTag()
  {
    return $this->imgTag;
  }
  public function getAdSImgTag()
  {
    return $this->sImgTag;
  }
  public function getAdAffiImgWidth()
  {
    return $this->affiImgWidth;
  }
  public function getAdAffiImgHeight()
  {
    return $this->affiImgHeight;
  }

  public function getAppId()
  {
    return $this->appId;
  }
  
  public function getAdInfo()
  {
    return [
      $this->name, $this->anken, $this->affiLink, $this->sLink, $this->aspName, $this->affiImg, $this->imgTag, $this->sImgTag, $this->affiImgWidth, $this->affiImgHeight
    ];
  }

  public function getHomeUrl()
  {
    return $this->homeUrl;
  }
}