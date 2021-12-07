<?php

namespace Drupal\stepcommerce\Controller;

use Drupal\commerce_cart\CartManager;
use Drupal\commerce_order\Entity\Order;
use Drupal\commerce_order\Entity\OrderItem;
use Drupal\commerce_product\Entity\Product;
use Drupal\commerce_product\Entity\ProductType;
use Drupal\commerce_product\Entity\ProductVariationType;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\profile\Entity\Profile;
use Drupal\user\Entity\User;
use NumberFormatter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\commerce_cart\CartProviderInterface;
use Drupal\commerce_cart\CartManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_checkout\CheckoutOrderManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\commerce_payment\CreditCard;
use Drupal\commerce_price\Price;

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
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;
  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * Drupal\commerce_checkout\CheckoutOrderManagerInterface definition.
   *
   * @var \Drupal\commerce_checkout\CheckoutOrderManagerInterface
   */
  protected $commerceCheckoutCheckoutOrderManager;

  /**
   * Constructs a new CartController object.
   *
   * @param \Drupal\commerce_cart\CartProviderInterface $cart_provider
   *   The cart provider.
   */
  public function __construct(CartManagerInterface $cart_manager,
                              CartProviderInterface $cart_provider,
                              AccountProxyInterface $current_user,
                              EntityTypeManagerInterface $entity_type_manager,
                              CheckoutOrderManagerInterface $commerce_checkout_checkout_order_manager) {
    $this->cartManager = $cart_manager;
    $this->cartProvider = $cart_provider;
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->commerceCheckoutCheckoutOrderManager = $commerce_checkout_checkout_order_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('commerce_cart.cart_manager'),
      $container->get('commerce_cart.cart_provider'),
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('commerce_checkout.checkout_order_manager')
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
    $data = ['status' => 'fail'];

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
        $data = ['status' => 'success'];
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
    $data = ['status' => 'fail'];

    if (is_numeric($uid)) {
      $storage_list = \Drupal::entityTypeManager()
        ->getStorage('commerce_order')
        ->loadByProperties(['uid' => $uid]);
      $storage = end($storage_list);

      if ($storage instanceof Order) {
        if ($storage->getState()->value == 'draft') {
          $data['order_id'] = $storage->id();
          $data['list'] = $storage->get('order_items')->getValue();

          foreach ($data['list'] as &$item) {
            $order_item = OrderItem::load($item['target_id']);

            if ($order_item instanceof OrderItem) {
              $item['name'] = $order_item->get('title')->value;
              $item['quantity'] = $order_item->get('quantity')->value;

              $price = $order_item->get('unit_price')->getValue()[0];

              $item['price']['number'] = $price['number'];
              $item['price']['currency_code'] = $price['currency_code'];
            }
          }
          $data['status'] = 'success';
        }
        else {
          $data['status'] = 'empty';
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
    $data = ['status' => 'fail'];

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
    $data = ['status' => 'fail'];

    if (is_numeric($uid) && is_numeric($order_id)) {
      $storage_list = \Drupal::entityTypeManager()
      ->getStorage('commerce_order')
      ->loadByProperties(['uid' => $uid]);
      $storage = end($storage_list);
      $cart_manager = \Drupal::service('commerce_cart.cart_manager');

      if ($cart_manager instanceof CartManager && $storage instanceof Order) {
        $order_count = count($storage->get('order_items')->getValue());
        $order_item = OrderItem::load($order_id);

        if ($order_item instanceof OrderItem) {
          $cart_manager->removeOrderItem($storage, $order_item);
          $after_count = count($storage->get('order_items')->getValue());

          $data = ['status' => $after_count === $order_count - 1];
        }
      }
    }

    return new JsonResponse($data);
  }

  /**
   * Checkout.
   *
   * @return string
   *   Return Hello string.
   */
  public function checkout(Request $request) {
    try {
      $content = json_decode($request->getContent(), TRUE,
        512, JSON_THROW_ON_ERROR);
    }
    catch (\JsonException $e) { }
    $order_id = $content['order_id'];
    $address_data = $content['address_data'];
    $status = 200;
    $responce = [];
    $storage = $this->entityTypeManager->getStorage('commerce_order');
    $order = $storage->load($order_id);

    if ($order instanceof Order) {
      $user = $this->currentUser->getAccount();
      $profiles = $order->billing_profile->referencedEntities();
      if (empty($profiles) && empty($address_data)) {
        // Return the Billing Step.
        $responce = $this->getBillingArray($order_id);
        if ($user->id() != 0) {
          $profile_storage = $this->entityTypeManager->getStorage('profile');
          $loaded = $profile_storage->loadByProperties(['uid' => $user->id()]);
          if (!empty($loaded) && is_array($loaded)) {
            $profile = end($loaded);
            $order->set('billing_profile', $profile);
            $order->save();
            return $this->checkout($order_id);
          }
        }
      }
      elseif (!empty($profiles[0])) {
        $responce['update_billing_info'] = $this->getBillingArray($order_id, $profiles[0]);
      }
      elseif (!empty($address_data)) {
        $responce['update_billing_info'] = $this->getBillingArray($order_id, $address_data);
      }

      // Get credit card fields.
      if (!empty($order->payment_method->target_id)) {
        $payment_method = $order->payment_method->referencedEntities()[0];
        if ($payment_method->reusable->value == 1) {
          $responce['existing_card'] = [
            'type' => $payment_method->card_type->value,
            'card' => $payment_method->card_number->value
          ];
          $responce['add_new_payment_method'] = $this->getCreditCardFields($order_id);
          $responce['review_order'] = [
            'action' => [
              'method' => 'GET',
              'uri' => '/api/checkout/'. $order_id .'/review-order',
              'step' => 'review_order',
            ],
          ];
          $responce['pay_for_order'] = [
            'action' => [
              'method' => 'GET',
              'uri' => '/api/checkout/'. $order_id .'/pay',
              'step' => 'pay',
            ],
          ];
        }
      }
      else {
        // check if there is a payment profile and add it to the order by default.
        if (!empty($profiles)) {
          if ($user->id() != 0) {
            $commerce_payment_method_storage = $this->entityTypeManager->getStorage('commerce_payment_method');
            try {
              $loaded = $commerce_payment_method_storage->loadByProperties(['uid' => $user->id()]);
              if (count($loaded) != 0) {
                if (count($loaded) == 1) {
                  $loaded_payment_method = reset($loaded);
                }
                else {
                  // The last one in the array.
                  $loaded_payment_method = end($loaded);
                }
                // Set the billing and payment method on the order.
                $loaded_billing_profile = $loaded_payment_method->billing_profile->referencedEntities()[0];
                $loaded_payment_method->id();
                $gate_way = $loaded_payment_method->payment_gateway->referencedEntities()[0];
                // added it to the order.
                $order->set('payment_method', $loaded_payment_method);
                $order->set('billing_profile', $loaded_billing_profile);
                //// todo check the check out workflow.
                $order->set('checkout_step', 'review');
                $order->set('payment_gateway', $gate_way);
                $order->save();
                //// return its self again
                return $this->checkout($order_id);
              }
              else {
                $order->set('checkout_step', 'review');
                $order->set('payment_gateway', 'manual_payment_gateway');
                $order->save();
              }
            }
            catch (Exception $e) {
              // probably log this.
            }
          }
          $responce['add_payment_method'] = $this->getCreditCardFields($order_id);
        }
        else {
          $order->set('checkout_step', 'review');
          $order->set('payment_gateway', 'manual_payment_gateway');
          $order->save();
        }
      }

      // check if has a payment profile
      if ($user->id() != 0) {
        //$responce[''] = ['billing' => 'billing'];
      }
      else {
        //$responce = ['message' => 'Guest checkout not ready yet'];
      }


      //$checkout_flow = $this->commerceCheckoutCheckoutOrderManager->getCheckoutFlow($order);
      //dump($checkout_flow->get('configuration'));
    }
    else {
      $responce = ['message' => 'order not found'];
      $status = 400;
    }
    return new JsonResponse($responce, $status);
  }


  public function getBillingArray($order_id, $profile = false) {

    //@TODO make this dynamic not hardcoded to where i live lol.
    $responce = [
      'action' => [
        'method' => 'POST',
        'uri' => '/api/checkout/billing-info',
        'step' => 'billing_info',
      ],
      'fields' => [
        'country_code' => [
          'name' => 'country_code',
          'label' => 'Country',
          'type' => 'select',
          'options' => [
            'UA' => 'Ukraine',
          ],
          'required' => TRUE,
        ],
        'administrative_area' => [
          'name' => 'administrative_area',
          'label' => 'Oblast',
          'type' => 'select',
          'options' => [
            'Crimea' => 'Crimea',
            'Vinnyts\'ka oblast' => 'Vinnyts\'ka oblast',
            'Volyns\'ka oblast' => 'Volyns\'ka oblast',
            'Dnipropetrovsk oblast' => 'Dnipropetrovsk oblast',
            'Donetsk oblast' => 'Donetsk oblast',
            'Zhytomyrs\'ka oblast' => 'Zhytomyrs\'ka oblast',
            'Zakarpats\'ka oblast' => 'Zakarpats\'ka oblast',
            'Zaporiz\'ka oblast' => 'Zaporiz\'ka oblast',
            'Ivano-Frankivs\'ka oblast' => 'Ivano-Frankivs\'ka oblast',
            'Kyiv city' => 'Kyiv city',
            'Kiev oblast' => 'Kiev oblast',
            'Kirovohrads\'ka oblast' => 'Kirovohrads\'ka oblast',
            'Luhans\'ka oblast' => 'Luhans\'ka oblast',
            'Lviv oblast' => 'Lviv oblast',
            'Mykolaivs\'ka oblast' => 'Mykolaivs\'ka oblast',
            'Odessa oblast' => 'Odessa oblast',
            'Poltavs\'ka oblast' => 'Poltavs\'ka oblast',
            'Rivnens\'ka oblast' => 'Rivnens\'ka oblast',
            'Sevastopol\' city' => 'Sevastopol\' city',
            'Sums\'ka oblast' => 'Sums\'ka oblast',
            'Ternopil\'s\'ka oblast' => 'Ternopil\'s\'ka oblast',
            'Kharkiv oblast' => 'Kharkiv oblast',
            'Khersons\'ka oblast' => 'Khersons\'ka oblast',
            'Khmel\'nyts\'ka oblast' => 'Khmel\'nyts\'ka oblast',
            'Cherkas\'ka oblast' => 'Cherkas\'ka oblast',
            'Chernivets\'ka oblast' => 'Chernivets\'ka oblast',
            'Chernihivs\'ka oblast' => 'Chernihivs\'ka oblast'
          ],
          'required' => TRUE,
        ],
        'locality' => [
          'name' => 'locality',
          'label' => 'Suburb',
          'type' => 'text',
          'required' => TRUE,
        ],
        'address_line1' => [
          'name' => 'address_line1',
          'label' => 'Address',
          'type' => 'text',
          'required' => TRUE,
        ],
        'address_line2' => [
          'name' => 'address_line2',
          'label' => 'Address line 2',
          'type' => 'text',
          'required' => FALSE,
        ],
        'postal_code' => [
          'name' => 'postal_code',
          'label' => 'Postal Code',
          'type' => 'string',
          'required' => TRUE,
        ],
        'given_name' => [
          'name' => 'given_name',
          'label' => 'First Name',
          'type' => 'string',
          'required' => TRUE,
        ],
        'family_name' => [
          'name' => 'family_name',
          'label' => 'Last Name',
          'type' => 'string',
          'required' => TRUE,
        ],
      ],
    ];
    if ($profile instanceof Profile) {
      foreach ($responce['fields'] as $key => $val) {
        if (!empty($profile->address->{$key})) {
          $responce['fields'][$key]['value'] = $profile->address->{$key};
        }
      }
    }
    elseif (is_array($profile)) {
      foreach ($responce['fields'] as $key => $val) {
        if (!empty($profile[$key])) {
          $responce['fields'][$key]['value'] = $profile[$key];
        }
      }
    }
    return $responce;
  }

  // This may update or create a billing profile and add to the order.
  public function postBilling(Request $request) {
    try {
      $content = json_decode($request->getContent(), TRUE,
        512, JSON_THROW_ON_ERROR);
    }
    catch (\JsonException $e) { }
    $order_id = $content['order_id'];
    $address_data = $content['address_data'];
    $status = 200;
    $storage = $this->entityTypeManager->getStorage('commerce_order');
    $order = $storage->load($order_id);
    if ($order instanceof Order) {
      // check user
      $user = $this->currentUser->getAccount();
      $profiles = $order->billing_profile->referencedEntities();
      $profile_storage = $this->entityTypeManager->getStorage('profile');
      $create = '';
      $update = '';
      if (empty($profiles)) {
        $create = TRUE;
        $update = FALSE;
      }
      else {
        $create = FALSE;
        $update = TRUE;
        if (count($profiles) == 1) {
          $profile = reset($profiles);
        }
        else {
          $profile_end = end($profiles);
          $profile = reset($profile_end);
        }
      }

      if (!empty($content)) {
        $data = $this->getBillingArray($order_id);
        $errors = [];
        foreach ($data['fields'] as $key => $val) {
          if ($val['required'] == TRUE) {
            if (!array_key_exists($key, $address_data)) {
              $errors[] = $val['label'] . ' is required';
            }
          }

          if ($val['type'] == 'select' && !empty($address_data[$key])) {
            $value = $address_data[$key];
            if (!array_key_exists($value, $val['options'])) {
              $errors[] = $val['label'] . ' not valid.' . $value;
            }
          }
        }
        if (count($errors) != 0) {
          $responce = $errors;
          $status = 400;
        }
        else {
          if ($create == TRUE) {
            $new_profile = $profile_storage->create([
              'type' => 'customer',
              'uid' => $user->id(),
            ]);
            $new_profile->set('address', $address_data);
            $new_profile->save();
            $order->set('billing_profile', $new_profile);
            $order->save();
            $responce = ['message' => 'create'];
            return $this->checkout(new Request($content));
          }
          if ($update == TRUE) {
            $profile->set('address', $address_data);
            $profile->save();
            $responce = ['message' => 'updated'];
            return $this->checkout(new Request($content));
          }
        }
      }
      else {
        $responce = ['message' => 'Invalid post object'];
        $status = 400;
      }
    }
    else {
      $responce = ['message' => 'order not found'];
      $status = 400;
    }
    return new JsonResponse($responce, $status);
  }

  //Get pwyment form
  function getCreditCardFields($order_id) {
    $result = [
      'action' => [
        'method' => 'POST',
        'uri' => '/api/checkout/'. $order_id .'/payment-method-create',
        'step' => 'payment_method_create',
      ],
    ];
    $months = [];
    for ($i = 1; $i < 13; $i++) {
      $month = str_pad($i, 2, '0', STR_PAD_LEFT);
      $months[$month] = $month;
    }
    // Build a year select list that uses a 4 digit key with a 2 digit value.
    $current_year_4 = date('Y');
    $current_year_2 = date('y');
    $years = [];
    for ($i = 0; $i < 10; $i++) {
      $years[$current_year_4 + $i] = $current_year_2 + $i;
    }

    $result['fields'] = [
      'number' => [
        'name' => 'number',
        'label' => 'Card number',
        'type' => 'string',
        'required' => TRUE,
      ],
      'month' => [
        'name' => 'month',
        'label' => 'Month',
        'type' => 'select',
        'options' => $months,
        'required' => TRUE,
      ],
      'year' => [
        'name' => 'year',
        'label' => 'Year',
        'type' => 'select',
        'options' => $years,
        'required' => TRUE,
      ],
      'security_code' => [
        'name' => 'security_code',
        'label' => 'CVV',
        'type' => 'string',
        'required' => TRUE,
      ],
    ];
    return $result;
  }

  public function postCreditCardToCreatePaymentMethod($order_id) {
    $stack = \Drupal::service('request_stack');
    $request = $stack->getCurrentRequest();
    $content = $request->getContent();
    $status = 200;
    $storage = $this->entityTypeManager->getStorage('commerce_order');
    $order = $storage->load($order_id);
    $user = $this->currentUser->getAccount();

    if (!empty($content) && $order instanceof Order) {
      $post_data = json_decode($content, TRUE);
      $data = $this->getCreditCardFields($order_id);
      $errors = [];
      foreach ($data['fields'] as $key => $val) {
        if ($val['required'] == TRUE) {
          if (!array_key_exists($key, $post_data)) {
            $errors[] = $val['label'] . ' is required';
          }
        }

        if ($val['type'] == 'select' && !empty($post_data[$key])) {
          $value = $post_data[$key];
          if (!array_key_exists($value, $val['options'])) {
            $errors[] = $val['label'] . ' not valid.' . $value;
          }
        }
      }
      if (count($errors) != 0) {
        $responce = $errors;
        $status = 400;
      }
      else {
        // validate card.
        $card_type = CreditCard::detectType($post_data['number']);
        if (!$card_type) {
          $errors[] = 'You have entered a credit card number of an unsupported card type.';
        }
        else {
          if (!CreditCard::validateNumber($post_data['number'], $card_type)) {
            $errors[] = 'You have entered an invalid credit card number.';
          }
          if (!CreditCard::validateExpirationDate($post_data['month'], $post_data['year'])) {
            $errors[] = 'You have entered an expired credit card.';
          }
          if (!CreditCard::validateSecurityCode($post_data['security_code'], $card_type)) {
            $errors[] = 'You have entered an invalid CVV.';
          }
        }

        if (count($errors) != 0) {
          $responce = $errors;
          $status = 400;
        }
        else {
          //$checkout_flow = $this->commerceCheckoutCheckoutOrderManager->getCheckoutFlow($order);
          //dump($checkout_flow);
          // @TODO get the checkout flow and pre fill this stuff..
          $commerce_payment_method_storage = $this->entityTypeManager->getStorage('commerce_payment_method');
          // $load = $commerce_payment_method_storage->load(5)->delete();
          // dump($load);
          $expires = CreditCard::calculateExpirationTimestamp($post_data['month'], $post_data['year']);
          $create = [
            'card_type' => $card_type->getId(),
            'billing_profile' => $order->billing_profile->referencedEntities()[0],
            'uid' => $user->id(),
            'reusable' => 1,
            'card_number' => $post_data['number'],
            'card_exp_month' => $post_data['month'],
            'card_exp_year' => $post_data['year'],
            'type' => 'credit_card',
            'payment_gateway_mode' => 'test',
            'payment_gateway' => 'test',
            'expires' => $expires,
          ];
          $created = $commerce_payment_method_storage->create($create);
          $created->save();
          $responce = $this->checkout($order_id);
          $order->set('payment_method', $created);
        }
      }
    }
    else {
      $responce = ['message' => 'order not found'];
      $status = 400;
    }
    return new JsonResponse($responce, $status);
  }

  function reviewOrderPrePayment($order_id) {
    $status = 200;
    $responce = ['message' => 'Review order'];
    $storage = $this->entityTypeManager->getStorage('commerce_order');
    $order = $storage->load($order_id);
    if ($order instanceof Order) {
      if ($order->checkout_step->value != 'review')  {
        // @TODO add more detaill but this enugh for now.
        $items = [];
        foreach ($order->order_items->referencedEntities() as $k => $obj) {
          $num = round($obj->quantity->value);
          //$new = round($num);
          $items[$k] = [
            'title'=> $obj->title->value,
            'quantity' => $num,
          ];
        }
        $price = $order->get('total_price')->getValue()[0];
        $total_number = $price['number'];
        $total_cc = $price['currency_code'];

        $responce['order_details'] = [
          'total_price' => [
            'number' => number_format($total_number, 2),
            'currency_code' =>  $total_cc,
          ],
          'order_items' => $items,
        ];
        // aka add billing add payment ect.

        $responce['pay_for_order'] = [
          'action' => [
            'method' => 'GET',
            'uri' => '/api/checkout/'. $order_id .'/pay',
            'step' => 'pay',
          ],
        ];
      }
      else {
        $responce = ['message' => 'order not in review state'];
        $status = 400;
      }
    }
    else {
      $responce = ['message' => 'order not found'];
      $status = 400;
    }
    return new JsonResponse($responce, $status);
  }

  // PAY.
  public function payForOrder($order_id) {
    $status = 200;
    $responce = ['message' => 'payment'];
    $storage = $this->entityTypeManager->getStorage('commerce_order');
    $order = $storage->load($order_id);
    if ($order instanceof Order) {
      if ($order->checkout_step->value == 'review')  {
        //@TODO add better validation here.
        $price = $order->get('total_price')->getValue()[0];
        $total_number = $price['number'];
        $total_cc = $price['currency_code'];
        $amount = new Price($total_number, $total_cc);

        $payment_method = $order->payment_method->referencedEntities()[0];
        $payment_gateway = $order->payment_gateway->referencedEntities()[0];
        $payment_gateway_plugin = $payment_gateway->getPlugin();

        $payment_storage = $this->entityTypeManager->getStorage('commerce_payment');
        $payment = $payment_storage->create([
          'state' => 'new',
          'amount' => $order->getTotalPrice(),
          'payment_gateway' => $payment_gateway->id(),
          'order_id' => $order->id(),
          'payment_method' => $order->payment_method->entity
        ]);

        $error = [];
        try {
          $payment_gateway_plugin->createPayment($payment);
          //$payment_gateway_plugin->capturePayment($payment, $amount);
        }
        catch (DeclineException $e) {
          \Drupal::logger('commerce_payment')->error($e->getMessage());
          $error[] = 'We encountered an error processing your payment method. Please verify your details and try again.';
        }
        catch (PaymentGatewayException $e) {
          \Drupal::logger('commerce_payment')->error($e->getMessage());
          $error[] = 'We encountered an unexpected error processing your payment method. Please try again later.';
        }

        if (count($error) != 0) {
          $responce = ['error' => $error];
          $status = 400;
        }
        else {
          $transition = $order->getState()->getTransitions();
          $order->getState()->applyTransition($transition['place']);
          $order->set('checkout_step', 'complete');
          $order->save();
          $responce = ['message' => 'payment sucess'];
        }

      }
      else {
        $responce = ['message' => 'order not in review state'];
        $status = 400;
      }
    }
    else {
      $responce = ['message' => 'order not found'];
      $status = 400;
    }
    return new JsonResponse($responce, $status);
  }

  /**
   * Reloads the given entity from the storage and returns it.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to be reloaded.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The reloaded entity.
   */
  protected function reloadEntity(EntityInterface $entity) {
    $controller = $this->entityTypeManager->getStorage($entity->getEntityTypeId());
    $controller->resetCache([$entity->id()]);
    return $controller->load($entity->id());
  }

}
