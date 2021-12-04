<?php

namespace Drupal\stepcommerce\Controller;

use Drupal\commerce_cart\CartManager;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\paragraphs\Entity\Paragraph;
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
      $categories[$key][] = [$product_type->id() => $product_type->label()];
    }

    return new JsonResponse($categories);
  }

  public function characteristic($id = NULL) {
    $data = NULL;

    if (is_numeric($id)) {
      $storage = Product::load($id);

      if ($storage instanceof Product) {
        $characteristic_list = $storage
          ->get('field_characteristic')
          ->getValue();

        foreach ($characteristic_list as $item) {
          $paragraph = Paragraph::load($item['target_id']);

          if ($paragraph instanceof Paragraph) {
            $name = $paragraph->get('field_name')->value;
            $val = $paragraph->get('field_value')->value;

            $data[] = ['name' => $name, 'value' => $val];
          }
        }
      }
    }

    return new JsonResponse($data);
  }

  public function cart($id = NULL) {
    $data = NULL;

    if (is_numeric($id)) {
      $storage = \Drupal::entityTypeManager()
        ->getStorage('commerce_order')
        ->loadByProperties(['uid' => $id])[1];

      if ($storage instanceof Order) {
        $data = $storage->get('order_items')->getValue();

        foreach ($data as &$item) {
          $order_item = OrderItem::load($item['target_id']);

          if ($order_item instanceof OrderItem) {
            $item['name'] = $order_item->get('title')->value;
            $item['quantity'] = $order_item->get('quantity')->value;

            $price = $order_item->get('unit_price')->getValue()[0];

            $item['price']['number'] = $price['number'];
            $item['price']['currency_code'] = $price['currency_code'];
          }
        }
      }
    }

    return new JsonResponse($data);
  }

  public function removeFromCart($uid = NULL, $order_id = NULL) {
    $data = ['success' => FALSE];

    if (is_numeric($uid) && is_numeric($order_id)) {
      $storage = \Drupal::entityTypeManager()
      ->getStorage('commerce_order')
      ->loadByProperties(['uid' => $uid])[1];
      $cart_manager = \Drupal::service('commerce_cart.cart_manager');

      if ($cart_manager instanceof CartManager && $storage instanceof Order) {
        $order_count = count($storage->get('order_items')->getValue());
        $order_item = OrderItem::load($order_id);

        if ($order_item instanceof OrderItem) {
          $cart_manager->removeOrderItem($storage, $order_item);
          $after_count = count($storage->get('order_items')->getValue());

          $data = ['success' => $after_count === $order_count - 1];
        }
      }
    }

    return new JsonResponse($data);
  }
}
