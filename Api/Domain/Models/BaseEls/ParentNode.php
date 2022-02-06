<?php
namespace AjaxSnippets\Api\Domain\Models\BaseEls;

class ParentNode
{
  public $id;
  private $name;
  private $anken;
  private $affiLink;
  private $sLink;
  private $aspName;
  private $affiImg;
  private $imgTag;
  private $sImgTag;

  public function __construct(
    int $id = null,
    string $name,
    string $anken,
    string $affiLink,
    string $sLink,
    string $aspName,
    string $affiImg,
    string $imgTag,
    string $sImgTag
  )
  {
    if($id === null){
      $this->id = 0; //0を設定していると自動でauto incrementしてくれる
    }else{
      $this->id = $id;
    }
    $this->name = $name;
    $this->anken =$anken;
    $this->affiLink=$affiLink;
    $this->sLink =$sLink;
    $this->aspName = $aspName;
    $this->affiImg = $affiImg;
    $this->imgTag = $imgTag;
    $this->sImgTag = $sImgTag;
  }

  //以下、ドメインの知識のみ
  public function id()
  {
    return $this->id;
  }

  public function name()
  {
    return $this->name;
  }

  public function anken()
  {
    return $this->anken;
  }

  public function affiLink()
  {
    return $this->affiLink;
  }
  
  public function sLink()
  {
    return $this->sLink;
  }
  
  public function aspName()
  {
    return $this->aspName;
  }
  
  public function affiImg()
  {
    return $this->affiImg;
  }
  
  public function imgTag()
  {
    return $this->imgTag;
  }
  
  public function sImgTag()
  {
    return $this->sImgTag;
  }

  public function setId(int $id)
  {
    //インクリメントなので重複チェックは不要
    // $this->aspName = $newName;
  }

  public function setName(string $name)
  {
    if(mb_strlen($name) > 30){ 
      return;
    }
    $this->name = $name;
  }

  public function setAnken(string $anken)
  {
    if(mb_strlen($anken) > 30){ 
      return;
    }
    $this->anken = $anken;
  }

  public function setAffiLink(string $affiLink)
  {
    if(mb_strlen($affiLink) > 1024){ 
      return;
    }
    $this->affiLink = $affiLink;
  }

  public function setSLink(string $sLink)
  {
    if(mb_strlen($sLink) > 1024){ 
      return;
    }
    $this->sLink = $sLink;
  }

  public function setAspName(string $aspName)
  {
    if(mb_strlen($aspName) > 10){ 
      return;
    }
    $this->aspName = $aspName;
  }
  
  public function setAffiImg(string $affiImg)
  {
    if(mb_strlen($affiImg) > 255){
      return;
    }
    $this->affiImg = $affiImg;
  }

  public function setImgTag(string $imgTag)
  {
    if(mb_strlen($imgTag) > 255){
      return;
    }
    $this->imgTag = $imgTag;
  }

  public function setSImgTag(string $sImgTag)
  {
    if(mb_strlen($sImgTag) > 255){
      return;
    }
    $this->sImgTag = $sImgTag;
  }
}

?>