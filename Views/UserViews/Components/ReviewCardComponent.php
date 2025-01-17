<?php

if (!defined('ABSPATH')) exit; ?>
<blockquote cite="review.quoteUrl" class="review-card fixed-card">
  <div class="review-card__rating">
    <span class="star filled"></span>
    <span class="star filled"></span>
    <span class="star filled"></span>
    <span class="star outlined"></span>
    <span class="star outlined"></span>
  </div>
  <div class="review-card__attribute">
    <span class="review-card__name">名前</span>
    <span class="review-card__sex">年齢 性別</span>
  </div>
  <div class="review-card__content">内容
  </div>
  <div class="review-card__content__readmore">----もっと読む----</div>
  <cite class="review-card__cite"><a href="review.quoteUrl">レビュー参照先</a></cite>
</blockquote>

<script>
  const reviewCard = document.querySelector('.review-card');
  const readMore = document.querySelector('.review-card__content__readmore');
  const content = document.querySelector('.review-card__content');
  readMore.addEventListener('click', () => {
    content.classList.toggle('expanded');
    readMore.textContent = content.classList.contains('expanded') ? '----閉じる----' : '----もっと読む----';
  });
</script>