<?php

namespace Drupal\stepcommerce\Controller;

use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductVariationType;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller with function for generating lists about Performance Reports.
 */
class StepCommerceController {

  public function categories() {
    $categories = [];
    $product_types = ProductType::loadMultiple();
    $product_variation_type =  ProductVariationType::loadMultiple();

    foreach ($product_types as $product_type) {
      $variation_name = $product_type->getVariationTypeId();
      $key = $product_variation_type[$variation_name]->label();
      $array_keys = array_keys($product_variation_type);

      $num = array_search($variation_name, $array_keys, TRUE);

      $categories[$num][$key][] = [$product_type->id() => $product_type->label()];
    }

    return new JsonResponse($categories);
  }
}
