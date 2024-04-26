
<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Infrastructure\QueryService\AffiLinkQueryService;
use AjaxSnippets\Api\Infrastructure\QueryService\AffiLinkCommand;

final class AffiLinkQueryServiceTest extends WP_UnitTestCase
{
  private $query;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->query = new AffiLinkQueryService();
	}

  public function testCreateSuitableUrl()
  {
    $content = '記載する内容';
    // 公式サイトのURLのテキストのみ返す場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, 'blue', 1, $content);
    $this->assertEquals("公式商品リンク", $this->query->affiLink($cmd));

    // 新規タブで開くテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, '', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_blank"
    >記載する内容</a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開かないテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 0, '', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_self"
    >記載する内容</a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開くボタンテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, 'blue', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_blank"
    >
      <button class="ajax_btn blue-ajax_btn">記載する内容</button>
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開かないボタンテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 0, 'red', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_self"
    >
      <button class="ajax_btn red-ajax_btn">記載する内容</button>
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

  }

  public function testCreateSuitableUrForItemLink()
  {
    $content = '記載する内容';
    // 公式サイトのURLのテキストのみ返す場合
    $cmd = new AffiLinkCommand(2, 'click_place', 1, 'blue', 1, $content);
    $this->assertEquals("公式商品リンク2", $this->query->affiLink($cmd));

    // 新規タブで開くテキストリンクの場合
    $cmd = new AffiLinkCommand(2, 'click_place', 1, '', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="2"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2&a8ejpredirect=%E5%85%AC%E5%BC%8F%E5%95%86%E5%93%81%E3%83%AA%E3%83%B3%E3%82%AF2"
      target="_blank"
    >記載する内容</a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開かないテキストリンクの場合
    $cmd = new AffiLinkCommand(2, 'click_place', 0, '', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="2"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2&a8ejpredirect=%E5%85%AC%E5%BC%8F%E5%95%86%E5%93%81%E3%83%AA%E3%83%B3%E3%82%AF2"
      target="_self"
    >記載する内容</a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開くボタンテキストリンクの場合
    $cmd = new AffiLinkCommand(2, 'click_place', 1, 'blue', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="2"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2&a8ejpredirect=%E5%85%AC%E5%BC%8F%E5%95%86%E5%93%81%E3%83%AA%E3%83%B3%E3%82%AF2"
      target="_blank"
    >
      <button class="ajax_btn blue-ajax_btn">記載する内容</button>
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開かないボタンテキストリンクの場合
    $cmd = new AffiLinkCommand(2, 'click_place', 0, 'red', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="2"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2&a8ejpredirect=%E5%85%AC%E5%BC%8F%E5%95%86%E5%93%81%E3%83%AA%E3%83%B3%E3%82%AF2"
      target="_self"
    >
      <button class="ajax_btn red-ajax_btn">記載する内容</button>
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

  }

  public function testCreateSuitableUrlIfContentNull()
  {
    $content = '';
    // 公式サイトのURLのテキストのみ返す場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, 'blue', 1, $content);
    $this->assertEquals("公式商品リンク", preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開くテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, '', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_blank"
    > 公式商品リンク</a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開かないテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 0, '', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_self"
    > 公式商品リンク</a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開くボタンテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, 'blue', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_blank"
    >
      <button class="ajax_btn blue-ajax_btn"> 公式商品リンク</button>
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 新規タブで開かないボタンテキストリンクの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 0, 'red', 0, $content);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_self"
    >
      <button class="ajax_btn red-ajax_btn"> 公式商品リンク</button>
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

  }

  public function testCreateSuitableBanner()
  {
    // 広告バナーの場合
    $cmd = new AffiLinkCommand(1, 'click_place', 1, '', 0, '', true);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="1"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T"
      target="_blank"
    >
    <img  border="0"  width="300"  height="250"  alt="ファイテン"  src="https://www20.a8.net/svt/bgt?aid=191121135078&wid=001&eno=01&mid=s00000013028001013000&mc=1">
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));

    // 商品別に画像が設定している場合
    $cmd = new AffiLinkCommand(2, 'click_place', 1, '', 0, '', true);
    $html =<<<EOT
    <click-log
    @click-record="clickRecord"
    ad-detail-id="2"
    place="click_place"
    >
    <a
      rel="nofollow noopener"
      href="https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2&a8ejpredirect=%E5%85%AC%E5%BC%8F%E5%95%86%E5%93%81%E3%83%AA%E3%83%B3%E3%82%AF2"
      target="_blank"
    >
    <img  border="0"  width="300"  height="250"  alt="ファイテン"  src="https://www.example.com">
    </a>
    <img
      border="0"
      width="1"
      height="1"
      src="https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2"
      alt=""
    >
    </click-log>
    EOT;
    $expected = preg_replace("/\r|\n/", "", $html);
    $this->assertEquals($expected, preg_replace("/\r|\n/", "", $this->query->affiLink($cmd)));
  }

  public function estCreateAppLink()
  {
//     item-id="{$detail_id}"
// noaffi="{$noaf}"
    $res  = $this->query->appLink(1);
    $this->assertEquals('appLink', $res);

    // アフィリエイトリンクを使用しない場合
    $res  = $this->query->appLink(1, true);

  }

  private function resetDatabase()
  {
    global $wpdb;
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $wpdb->insert(PLUGIN_DB_PREFIX . "ads",[
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
      'app_id' => 1
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->insert(PLUGIN_DB_PREFIX . "ad_details", [
      'id' => 1,
      'ad_id' => 1,
      'item_name' => '商品名',
      'official_item_link' => '公式商品リンク',
      'affi_item_link' => 'アフィリ商品リンク',
      'detail_img' => 'https://www.example.com',
      'amazon_asin' => 'アマゾンASIN',
      'rakuten_id' => '楽天ID',
      'review' => '商品レビュー',
      'is_show_url' => 1,
      'same_parent' => 1
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . "ad_details", [
      'id' => 2,
      'ad_id' => 1,
      'item_name' => '商品名2',
      'official_item_link' => '公式商品リンク2',
      'affi_item_link' => 'アフィリ商品リンク2',
      'detail_img' => 'https://www.example.com',
      'amazon_asin' => 'アマゾンASIN2',
      'rakuten_id' => '楽天ID2',
      'review' => '商品レビュー2',
      'is_show_url' => 1,
      'same_parent' => 0
    ]);

    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 1,
      'ad_detail_id' => 2,
      'factor' => 'おすすめ度',
      'rate' => 4.4,
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 2,
      'ad_detail_id' => 2,
      'factor' => 'ダメダメ度',
      'rate' => 1.1,
      'sort_order' => 1,
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_info");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 1,
      'ad_detail_id' => 2,
      'title' => 'URL',
      'content' => 'https://www.example.com',
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 2,
      'ad_detail_id' => 2,
      'title' => '販売元',
      'content' => 'まるまる商会',
      'sort_order' => 1,
    ]);

    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asps");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'asps', [
      'id'=>1,
      'asp_name'=>'a8',
      'connect_string'=>'&a8ejpredirect='
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'asps', [
      'id'=>2,
      'asp_name'=>'afb',
      'connect_string'=>''
    ]);

    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'apps', [
      'id'=>1,
      'name'=>'アプリ名',
      'img'=>'https://www.image.com',
      'dev'=>'開発元の名前',
      'ios_link'=>'https://www.ios.com',
      'android_link'=>'https://www.android.com',
      'web_link'=>'https://www.web.com',
      'ios_affi_link'=>'https://www.ios-affi.com',
      'android_affi_link'=>'https://www.android-affi.com',
      'web_affi_link'=>'https://www.web-affi.com',
      'article'=>'記事',
      'app_order'=>1,
      'app_price'=>100
    ]);
  }
}
