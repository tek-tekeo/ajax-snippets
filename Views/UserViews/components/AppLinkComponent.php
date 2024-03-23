<?php
namespace AjaxSnippets\Views\UserViews\Components;

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;

class AppLinkComponent
{
  private $link;
  private $linkImg;

  public function __construct(private App $app, private Ad $ad, bool $noaf = false){

    //UAによる判断、アフィリエイト化するかの判断
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if ( preg_match('/Android/ui', $ua) ) {
      //Android系の端末
      ($noaf == 0) ? $this->link = $app->getAndroidAffiLink() : $this->link = $app->getAndroidLink();
      $this->linkImg = 'https://nabettu.github.io/appreach/img/gplay_ja.png';

    }else if ( preg_match('/iPhone|iPod|iPad/ui', $ua) ) {
      //iOS系の端末
      ($noaf == 0) ? $this->link = $app->getIosAffiLink() : $this->link = $app->getIosLink();
      $this->linkImg = 'https://nabettu.github.io/appreach/img/itune_ja.svg';
    }else{
      //Webブラウザからのアクセス
      ($noaf == 0) ? $this->link = $ad->getAffiLink() : $this->link = $app->getWebLink();
      $this->linkImg = '';
    }
  }

  public function getAppLinkCode()
  {
    if($this->linkImg != ''){
      $linkBtn =<<< EOP
      <img style="height: 40px; width: 135px;" src="{$this->linkImg}" alt="" />
      EOP;
    }else{
      $linkBtn =<<< EOB
      <button
        class="c-button"
      >
      公式サイトへ
      </button>
      EOB;
    }

  $rep =<<<EOT
  <a 
    class="applink"
    href="{$this->link}"
    rel="nofollow"
  >
    <div class="applink_box">
      <div class="applink_box_item1">
        <img src="{$this->app->getImage()}" alt="{$this->app->getName()}のアイコン'"/></div>
      <div class="applink_box_item2">
      <div class="fz-16px applink_box_title">{$this->app->getName()}</div>
      <div class="fz-12px">開発元：{$this->app->getDeveloper()}</div>
      {$linkBtn}
      </div>
    </div>
  </a>
  EOT;

  return $rep;
  }
}