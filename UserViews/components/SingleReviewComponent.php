<?php

if (!defined('ABSPATH')) exit; ?>

<div class="singleReview">
  <?= $banner ?>
  <chart-js
    class="secondItem"
    :rchart='<?=json_encode($rchart)?>'
    name="<?=$name?>"
    color="<?=$color?>"
    title="<?=$title?>"
  >
  </chart-js>
</div>
<table class="singleReview_table">
  <tbody>
  <?php foreach ($info as $item) : ?>
    <tr>
      <th><?= $item['title']?></th>
      <td><?= $item['content']?></td>
    </tr>
  <?php endforeach; ?>
  <?php if ($adDetail->getIsShowUrl()) : ?>
    <tr>
      <th>公式サイト</th>
      <td>
        <?= $text ?>
      </td>
    </tr>
  <?php endif; ?>
  </tbody>
</table>
<?php if ($isReview) : ?>
<span>
<?= wpautop($adDetail->getReview()) ?>
</span>
<?php endif; ?>