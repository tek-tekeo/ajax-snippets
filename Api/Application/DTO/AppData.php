<?php
namespace AjaxSnippets\Api\Application\DTO;

use AjaxSnippets\Api\Domain\Models\App\App;

class AppData
{
  public int $id;
  public string $name;
  public string $img;
  public string $dev;
  public string $iosLink;
  public string $androidLink;
  public string $webLink;
  public string $iosAffiLink;
  public string $androidAffiLink;
  public string $webAffiLink;
  public string $article;
  public int $appOrder;
  public int $appPrice;
  
  public function __construct(App $app)
  {
    $appId = $app->getId();
    $this->id = $appId->getId();
    $this->name = $app->getName();
    $this->img = $app->getImage();
    $this->dev = $app->getDeveloper();
    $this->iosLink = $app->getIosLink();
    $this->androidLink = $app->getAndroidLink();
    $this->webLink = $app->getWebLink();
    $this->iosAffiLink = $app->getIosAffiLink();
    $this->androidAffiLink = $app->getAndroidAffiLink();
    $this->webAffiLink = $app->getWebAffiLink();
    $this->article = $app->getArticle();
    $this->appOrder = $app->getAppOrder();
    $this->appPrice = $app->getAppPrice();
  }
}