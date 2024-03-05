<?php
namespace AjaxSnippets\Api\Application\DTO;

use AjaxSnippets\Api\Domain\Models\App\App;

class AppData
{
  public int $id;
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
  
  public function __construct(App $asp)
  {
    $aspId = $asp->getId();
    $this->id = $aspId->getId();
    $this->img = $asp->getImage();
    $this->dev = $asp->getDeveloper();
    $this->iosLink = $asp->getIosLink();
    $this->androidLink = $asp->getAndroidLink();
    $this->webLink = $asp->getWebLink();
    $this->iosAffiLink = $asp->getIosAffiLink();
    $this->androidAffiLink = $asp->getAndroidAffiLink();
    $this->webAffiLink = $asp->getWebAffiLink();
    $this->article = $asp->getArticle();
    $this->appOrder = $asp->getAppOrder();
    $this->appPrice = $asp->getAppPrice();

  }
}