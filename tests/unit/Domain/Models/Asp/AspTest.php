<?php
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

class AspTest extends WP_UnitTestCase
{
  public function testGetAspData()
  {
    $aspId = new AspId(1);
    $asp = new Asp($aspId, 'a8', '&aaa');
    $this->assertEquals(new AspId(1), $asp->getAspId());
    $this->assertEquals('a8', $asp->getAspName());

    $asp->changeAspName('a9');
    $this->assertEquals('a9', $asp->getAspName());

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('asp name has been exceeded 10 characters');
    $this->expectExceptionCode(500);
    $asp->changeAspName('tooooooooooo_long_asp_name');
  }

  public function testGetEntity()
  {
    $aspId = new AspId(1);
    $asp = new Asp($aspId, 'a8', '&aaa');
    $this->assertEquals([
      'id' => 1,
      'asp_name' => 'a8',
      'connect_string' => '&aaa'
    ], $asp->entity());
  }
}