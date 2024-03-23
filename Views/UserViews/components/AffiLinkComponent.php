<?php


if (!defined('ABSPATH')) exit; ?>
<a
  rel="nofollow noopener"
  href="<?=$affiLink?>"
  target="<?php echo $cmd->getNewTab() ? '_blank' : '_self'; ?>"
>
<?=$content?>
</a>
<?php if($ad->getSImgTag()): ?>
<img
  border="0"
  width="1"
  height="1"
  src="<?=$ad->getSImgTag()?>"
  alt=""
>
<?php endif; ?>