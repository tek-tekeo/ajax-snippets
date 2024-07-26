<?php

$brandName = $ad->getName();
if($adDetail->getItemName() != ''){
  $itemName = '"name":"'.$adDetail->getItemName().'",';
}else{
  $itemName = '"name":"匿名",';
}
if($adDetail->getReview() != ''){
$description = '"description": "'.wp_strip_all_tags($adDetail->getReview(), true).'",';
}
if($adDetail->getDetailImg() != ''){
$image = '"image":"'.$adDetail->getDetailImg().'",';
}
$reviewScript = '';
foreach($data->reviews as $review){
  $reviewScript .= <<<EOT
  {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "{$review->ratingValue}"
    },
    "author": {
      "@type": "Person",
      "name": "{$review->name}"
    }
  },
  EOT;
}
$reviewScript = rtrim($reviewScript, ',');
if (!defined('ABSPATH')) exit; ?>

<script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "Product",
    <?=$itemName?>
    "brand": {
      "@type": "Brand",
      "name": "<?=$brandName?>"
    },
    <?=$description?>
    <?=$image?>
    "review": [
      <?=$reviewScript?>
    ],
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "<?=$data->ratingValue?>",
      "bestRating": "<?=$data->bestRating?>",
      "ratingCount": "<?=$data->ratingCount?>"
    }
}
</script>