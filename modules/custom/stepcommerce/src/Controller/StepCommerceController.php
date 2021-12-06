<?php

namespace Drupal\stepcommerce\Controller;

use Drupal\commerce_cart\CartManager;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\commerce_cart\CartProviderInterface;
use Drupal\commerce_cart\CartManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller with function for generating lists about Performance Reports.
 */
class StepCommerceController extends ControllerBase {

  /**
   * The cart manager.
   *
   * @var \Drupal\commerce_cart\CartManagerInterface
   */
  protected $cartManager;

  /**
   * The cart provider.
   *
   * @var \Drupal\commerce_cart\CartProviderInterface
   */
  protected $cartProvider;

  /**
   * Constructs a new CartController object.
   *
   * @param \Drupal\commerce_cart\CartProviderInterface $cart_provider
   *   The cart provider.
   */
  public function __construct(CartManagerInterface $cart_manager,CartProviderInterface $cart_provider) {
    $this->cartManager = $cart_manager;
    $this->cartProvider = $cart_provider;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('commerce_cart.cart_manager'),
      $container->get('commerce_cart.cart_provider')
    );
  }

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

  public function cart(Request $request) {
    try {
      $content = json_decode($request->getContent(), TRUE,
        512, JSON_THROW_ON_ERROR);
    }
    catch (\JsonException $e) { }
    $uid = $content['user_id'] ?? NULL;
    $data = NULL;

    if (is_numeric($uid)) {
      $storage = \Drupal::entityTypeManager()
        ->getStorage('commerce_order')
        ->loadByProperties(['uid' => $uid])[1];

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

  public function addToCart(Request $request) {
    try {
      $content = json_decode($request->getContent(), TRUE,
        512, JSON_THROW_ON_ERROR);
    }
    catch (\JsonException $e) { }
    $uid = $content['user_id'] ?? NULL;
    $item_id = $content['item_id'] ?? NULL;
    $data = ['success' => FALSE];

    $productObj = Product::load($item_id);
    $user_account = User::load($uid);

    $product_variation_id = $productObj->get('variations')
      ->getValue()[0]['target_id'];
    $storeId = $productObj->get('stores')->getValue()[0]['target_id'];
    $variationobj = \Drupal::entityTypeManager()
      ->getStorage('commerce_product_variation')
      ->load($product_variation_id);
    $store = \Drupal::entityTypeManager()
      ->getStorage('commerce_store')
      ->load($storeId);

    $cart = $this->cartProvider->getCart('default', $store, $user_account);

    if (!$cart) {
      $cart = $this->cartProvider->createCart('default', $store, $user_account);

    }

    $line_item_type_storage = \Drupal::entityTypeManager()
      ->getStorage('commerce_order_item_type');
    // Process to place order programatically.
    $cart_manager = \Drupal::service('commerce_cart.cart_manager');
    $line_item = $cart_manager->addEntity($cart, $variationobj);

    return new JsonResponse($data);
  }

  public function removeFromCart(Request $request) {
    try {
      $content = json_decode($request->getContent(), TRUE,
        512, JSON_THROW_ON_ERROR);
    }
    catch (\JsonException $e) { }
    $uid = $content['user_id'] ?? NULL;
    $order_id = $content['order_id'] ?? NULL;
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
