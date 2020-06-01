<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/

namespace Tygh\Addons\MarketplaceOrderSync\DataTransferObjects;

use Illuminate\Contracts\Support\Arrayable;
use Tygh\Addons\MarketplaceOrderSync\Collections\ProductCollection;

final class Order implements Arrayable
{
    /**
     * @var \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Customer
     */
    public $customer;

    /**
     * @var \Tygh\Addons\MarketplaceOrderSync\Collections\ProductCollection
     */
    public $products;

    /**
     * @var bool
     */
    private $reset_total;

    public function __construct(Customer $customer, ProductCollection $products, $reset_total = true)
    {
        $this->customer = $customer;
        $this->products = $products;
        $this->reset_total = $reset_total;
    }

    /**
     * @param array<string, string> $order_data
     *
     * @return \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Order
     */
    public static function fromArray(array $order_data)
    {
        $customer = null;
        if (isset($order_data['customer'])) {
            $customer = $order_data['customer'];
            if (!$customer instanceof Customer) {
                $customer = Customer::fromArray($customer);
            }
        }

        $products = null;
        if (isset($order_data['products']) && $order_data['products']) {
            $products = new ProductCollection();
            foreach ($order_data['products'] as $product) {
                if (!$products instanceof Product) {
                    $product = Product::fromArray($product);
                }
                $products[] = $product;
            }
        }

        $reset_total = true;
        if (isset($order_data['reset_total'])) {
            $reset_total = (bool) $order_data['reset_total'];
        }

        return new self($customer, $products, $reset_total);
    }

    public function toArray()
    {
        return [
            'customer'          => $this->customer->toArray(),
            'products'          => $this->products->toArray(),
            'reset_order_total' => (int) $this->reset_total,
        ];
    }
}