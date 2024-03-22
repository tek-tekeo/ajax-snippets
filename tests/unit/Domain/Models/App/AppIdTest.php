<?php
use AjaxSnippets\Api\Domain\Models\App\AppId;

final class AppIdTest extends WP_UnitTestCase
{
  public function testAppIdClass(): void
  {
    $appId = new AppId();
    $this->assertEquals(new AppId(0), $appId);
    $this->assertInstanceOf(AppId::class, new AppId());

    $appId = new AppId(2);
    $this->assertEquals(new AppId(2), $appId);
    $this->assertInstanceOf(AppId::class, new AppId(2));
  }
}