<?php
namespace AjaxSnippets\Api\Domain\Models\BaseEls;

class App
{
  public int $appId;
  private string $img;
  private string $dev;
  private string $iosLink;
  private string $androidLink;
  private string $webLink;
  private string $iosAffiLink;
  private string $androidAffiLink;
  private string $webAffiLink;
  private string $article;
  private int $appOrder;
  private int $appPrice;

  public function __construct(
    int $appId = null,
    string $img,
    string $dev,
    string $iosLink,
    string $androidLink,
    string $webLink,
    string $iosAffiLink,
    string $androidAffiLink,
    string $webAffiLink,
    string $article,
    int $appOrder,
    int $appPrice
  )
  {
    if($appId === null){
      $this->appId = 0; //0を設定していると自動でauto incrementしてくれる
    }else{
      $this->appId = $appId;
    }
    $this->img = $img;
    $this->dev =$dev;
    $this->iosLink=$iosLink;
    $this->androidLink =$androidLink;
    $this->webLink = $webLink;
    $this->iosAffiLink = $iosAffiLink;
    $this->androidAffiLink = $androidAffiLink;
    $this->webAffiLink = $webAffiLink;
    $this->article = $article;
    $this->appOrder = $appOrder;
    $this->appPrice = $appPrice;
  }

  //以下、ドメインの知識のみ
  public function appId():int
  {
    return $this->appId;
  }

  public function img():string
  {
    return $this->img;
  }

  public function dev():string
  {
    return $this->dev;
  }

  public function iosLink():string
  {
    return $this->iosLink;
  }
  
  public function androidLink():string
  {
    return $this->androidLink;
  }
  
  public function webLink():string
  {
    return $this->webLink;
  }
  
  public function iosAffiLink():string
  {
    return $this->iosAffiLink;
  }
  
  public function androidAffiLink():string
  {
    return $this->androidAffiLink;
  }

  public function webAffiLink():string
  {
    return $this->webAffiLink;
  }
  
  public function article():string
  {
    return $this->article;
  }

  public function appOrder():int
  {
    return $this->appOrder;
  }

  public function appPrice():int
  {
    return $this->appPrice;
  }


  public function setAppId(int $appId)
  {
    //インクリメントなので重複チェックは不要
  }

  public function setImg(string $img)
  {
    $this->img = $img;
  }

  public function setDev(string $dev)
  {
    $this->dev = $dev;
  }

  public function setIosLink(string $iosLink)
  {
    $this->iosLink = $iosLink;
  }

  public function setAndroidLink(string $androidLink)
  {
    $this->androidLink = $androidLink;
  }

  public function setWebLink(string $webLink)
  {
    $this->webLink = $webLink;
  }
  
  public function setIosAffiLink(string $iosAffiLink)
  {
    $this->iosAffiLink = $iosAffiLink;
  }
  public function setAndroidAffiLink(string $androidAffiLink)
  {
    $this->androidAffiLink = $androidAffiLink;
  }
  public function setArticle(string $article)
  {
    $this->article = $article;
  }

  public function setAppOrder(int $appOrder)
  {
    $this->appOrder = $appOrder;
  }
  public function setAppPrice(int $appPrice)
  {
    $this->appPrice = $appPrice;
  }
}

?>