<?php
namespace AjaxSnippets\Api\Domain\Models\App;

use AjaxSnippets\Api\Domain\Models\App\AppId;

class App
{
  public function __construct(
    private AppId $id,
    private string $img,
    private string $dev,
    private string $iosLink,
    private string $androidLink,
    private string $webLink,
    private string $iosAffiLink,
    private string $androidAffiLink,
    private string $webAffiLink,
    private string $article,
    private int $appOrder,
    private int $appPrice
  ){}

  public function getId(): AppId
  {
    return $this->id;
  }

  public function getImage(): string
  {
    return $this->img;
  }

  public function getDeveloper(): string
  {
    return $this->dev;
  }

  public function getIosLink(): string
  {
    return $this->iosLink;
  }
  
  public function getAndroidLink(): string
  {
    return $this->androidLink;
  }
  
  public function getWebLink(): string
  {
    return $this->webLink;
  }
  
  public function getIosAffiLink(): string
  {
    return $this->iosAffiLink;
  }
  
  public function getAndroidAffiLink(): string
  {
    return $this->androidAffiLink;
  }

  public function getWebAffiLink(): string
  {
    return $this->webAffiLink;
  }
  
  public function getArticle(): string
  {
    return $this->article;
  }

  public function getAppOrder(): int
  {
    return $this->appOrder;
  }

  public function getAppPrice(): int
  {
    return $this->appPrice;
  }

  public function entity(): array
  {
    return [
      'id' => $this->id->getId(),
      'img' => $this->img,
      'dev' => $this->dev,
      'ios_link' => $this->iosLink,
      'android_link' => $this->androidLink,
      'web_link' => $this->webLink,
      'ios_affi_link' => $this->iosAffiLink,
      'android_affi_link' => $this->androidAffiLink,
      'web_affi_link' => $this->webAffiLink,
      'article' => $this->article,
      'app_order' => $this->appOrder,
      'app_price' => $this->appPrice
    ];
  }
}

?>