<?php
use AjaxSnippets\UserViews\RedirectSystem;
use AjaxSnippets\UserViews\RedirectCommand;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailChartRepository;

final class RedirectSystemTest extends WP_UnitTestCase
{
  private $redirectSystem;

	public function setUp():void
	{
		parent::setUp();
    $this->resetDatabase();
    global $diContainer;
    $this->redirectSystem = RedirectSystem::getInstance($diContainer);

	}

	protected function resetDatabase()
	{
		global $wpdb;
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asps");
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "logs");
		
    $wpdb->insert(PLUGIN_DB_PREFIX . "ads", [
      'id' => 1,
      'name' => 'ファイテン',
      'anken' => 'phiten',
      'affi_link' => 'https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T',
      's_link' => 'https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2',
      'asp_id' => 1,
      'affi_img' => 'https://www20.a8.net/svt/bgt?aid=191121135078&wid=001&eno=01&mid=s00000013028001013000&mc=1',
      'img_tag' => 'https://www12.a8.net/0.gif?a8mat=35SE0F+1AFTYQ+2SIW+614CX',
      's_img_tag' => 'https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2',
      'affi_img_width' => 300,
      'affi_img_height' => 250,
      'app_id' => 0
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . "ads", [
      'id' => 2,
      'name' => 'じぶんクリニック',
      'anken' => 'jibun',
      'affi_link' => 'affi_jubun_link',
      's_link' => '',
      'asp_id' => 2,
      'affi_img' => 'https://www27.a8.net/svt/bgt?aid=231219654871&wid=001&eno=01&mid=s00000022787002017000&mc=1',
      'img_tag' => 'https://www16.a8.net/0.gif?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T',
      's_img_tag' => '',
      'affi_img_width' => 300,
      'affi_img_height' => 250,
      'app_id' => 0
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . "ad_details", [
      'id' => 1,
      'ad_id' => 2,
      'item_name' => '',
      'official_item_link' => '',
      'affi_item_link' => '',
      'detail_img' => '',
      'amazon_asin' => '',
      'rakuten_id' => '',
      'rchart' => '',
      'info' => '',
      'review' => '',
      'is_show_url' => 0,
      'same_parent' => 1
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . "ad_details", [
      'id' => 2,
      'ad_id' => 1,
      'item_name' => '',
      'official_item_link' => '',
      'affi_item_link' => '',
      'detail_img' => '',
      'amazon_asin' => '',
      'rakuten_id' => '',
      'rchart' => '',
      'info' => '',
      'review' => 'ファイテン ネックレスのレビュー',
      'is_show_url' => 1,
      'same_parent' => 1
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . "ad_details", [
      'id' => 3,
      'ad_id' => 1,
      'item_name' => 'freeasy ハーフバスタオル',
      'official_item_link' => 'https://www.phiten-store.com/item/0424TU604000.html',
      'affi_item_link' => '',
      'detail_img' => 'https://www.phiten-store.com/client_info/PHITEN/itemimage/0424TU604000/0424TU604000_01.jpg',
      'amazon_asin' => 'B07QKJZ3ZP',
      'rakuten_id' => 'phiten:10000001',
      'rchart' => '',
      'info' => '',
      'review' => 'ファイテン ネックレスのレビュー',
      'is_show_url' => 1,
      'same_parent' => 0
    ]);
    $wpdb->replace(PLUGIN_DB_PREFIX . "asps", [
      'id' => 1,
      'asp_name' => 'a8',
      'connect_string' => '&a8ejpredirect='
    ]);
    $wpdb->replace(PLUGIN_DB_PREFIX . "asps", [
      'id' => 2,
      'asp_name' => 'afb',
      'connect_string' => ''
    ]);


	}

  public function testIsRedirectLink()
  {
    $req = 'link/affi?no=128&pl=youtube';
    $res = $this->redirectSystem->isRedirectLink($req);
    $this->assertEquals(['link/affi?no=128&pl=youtube', 'affi','128','youtube'], $res);
    
    $req = '/affi?no=128&pl=youtube';
    $res = $this->redirectSystem->isRedirectLink($req);
    $this->assertFalse($res);
  }

  public function estHandle()
  {
    // A8のメインリンクを取得する場合
    $req = 'link/affi?no=2&pl=a8mainplace';
    $res = $this->redirectSystem->handle($req);
    $this->assertEquals('https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T', $res);

    // A8の商品リンクを取得する場合
    $req = 'link/affi?no=3&pl=youtube';
    $res = $this->redirectSystem->handle($req);
    $this->assertEquals('https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2&a8ejpredirect='.urlencode("https://www.phiten-store.com/item/0424TU604000.html"), $res);

    //その他ASPのリンクを取得する場合
    $req = 'link/affi?no=1&pl=youtube';
    $res = $this->redirectSystem->handle($req);
    $this->assertEquals('affi_jubun_link', $res);

    // ログの登録を確認する
    global $wpdb;
    $res = $wpdb->get_results("SELECT * FROM " . PLUGIN_DB_PREFIX . "logs");
    $this->assertEquals(3, count($res));
  }

}