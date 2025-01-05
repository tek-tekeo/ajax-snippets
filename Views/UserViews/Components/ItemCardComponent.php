<?php

if (!defined('ABSPATH')) exit; ?>

<div class="item-card__container">
  <?php if (!$itemCard['onSale']): ?>
    <div class="item-card__label">販売終了</div>
  <?php endif; ?>
  <div class="item-card__image">
    <?= $itemCard['image'] ?>
  </div>
  <div class="item-card__title">
    <click-log
      @click-record="clickRecord"
      ad-detail-id="<?= $itemCard['id'] ?>"
      place="official-item_card_text">
      <a rel="nofollow noopener" target="_blank" href=" <?= $itemCard['officialLink'] ?>"><?= $itemCard['title'] ?></a>
    </click-log>
  </div>
  <div class="item-card__saler">販売元：<?= $itemCard['saler'] ?></div>
  <?php if ($itemCard['onSale']): ?>
    <div class="item-card__button__wrap">
      <click-log
        @click-record="clickRecord"
        ad-detail-id="<?= $itemCard['id'] ?>"
        place="official-item_card_btn">
        <a rel="nofollow noopener" target="_blank" href="<?= $itemCard['officialLink'] ?>" class="item-card__button shoplinkofficial">公式サイト</a>
      </click-log>
    </div>
  <?php endif; ?>
  <div class="item-card__button__wrap">
    <click-log
      @click-record="clickRecord"
      ad-detail-id="<?= $itemCard['id'] ?>"
      place="amazon-item_card_btn">
      <a rel="nofollow noopener" target="_blank" href="<?= $itemCard['amazonLink'] ?>" class="item-card__button shoplinkamazon">Amazon</a>
    </click-log>
  </div>
  <div class="item-card__button__wrap">
    <click-log
      @click-record="clickRecord"
      ad-detail-id="<?= $itemCard['id'] ?>"
      place="rakuten-item_card_btn">
      <a rel="nofollow noopener" target="_blank" href="<?= $itemCard['rakutenLink'] ?>" class="item-card__button shoplinkrakuten">楽天</a>
    </click-log>
  </div>
</div>