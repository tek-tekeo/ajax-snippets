<?php
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;

final class AppTest extends WP_UnitTestCase
{
  private App $app;
  public function setUp(): void
  {
    parent::setUp();
    $appId = new AppId();
		$this->app = new App(
      $appId,
      'imagestring',
      'dev',
      'iosLink',
      'androidLink',
      'webLink',
      'iosAffiLink',
      'androidAffiLink',
      'webAffiLink',
      'article',
      300,
      250
    );
  }

  public function testGetAppValues(): void
  {
    $this->assertEquals(new AppId(0), $this->app->getId());
    $this->assertEquals('imagestring', $this->app->getImage());
    $this->assertEquals('dev', $this->app->getDeveloper());
    $this->assertEquals('iosLink', $this->app->getIosLink());
    $this->assertEquals('androidLink', $this->app->getAndroidLink());
    $this->assertEquals('webLink', $this->app->getWebLink());
    $this->assertEquals('iosAffiLink', $this->app->getIosAffiLink());
    $this->assertEquals('androidAffiLink', $this->app->getAndroidAffiLink());
    $this->assertEquals('webAffiLink', $this->app->getWebAffiLink());
    $this->assertEquals('article', $this->app->getArticle());
    $this->assertEquals(300, $this->app->getAppOrder());
    $this->assertEquals(250, $this->app->getAppPrice());
  }
}