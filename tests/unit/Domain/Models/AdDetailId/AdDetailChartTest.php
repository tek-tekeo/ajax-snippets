
<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;


final class AdDetailChartTest extends WP_UnitTestCase
{

  public function testAdDetailChart()
  {
    $chart = new AdDetailChart(0, new AdDetailId(1), 'おすすめ度', 4.4);
    $this->assertEquals(
      [
        'id' => 0,
        'ad_detail_id' => 1,
        'factor' => 'おすすめ度',
        'rate' => 4.4,
        'sort_order' => 0,
      ], $chart->entity());
  }
}