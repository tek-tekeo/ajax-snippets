<?php
$showChart = (count($rchart) >= 3);

if (!defined('ABSPATH')) exit; ?>

<div class="singleReview">
  <?= $banner ?>
  <?php if ($showChart) : ?>
    <chart-js
      class="secondItem"
      :rchart='<?= json_encode($rchart) ?>'
      name="<?= $name ?>"
      color="<?= $color ?>"
      title="<?= $title ?>">
    </chart-js>
  <?php endif; ?>
</div>
<table class="singleReview_table">
  <tbody>
    <?php foreach ($info as $item) : ?>
      <tr>
        <th><?= wpautop($item['title']) ?></th>
        <td><?= wpautop($item['content']) ?></td>
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
    <?= wpautop(do_shortcode($adDetail->getReview())) ?>
  </span>
<?php endif; ?>
<?php if ($isItemCard) : ?>
  <span>
    <?= do_shortcode($itemCardShortCode) ?>
  </span>
<?php endif; ?>