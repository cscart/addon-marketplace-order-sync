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

final class Product implements Arrayable
{
    /**
     * @var string
     */
    public $external_id;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\License
     */
    public $license;

    /**
     * Product constructor.
     *
     * @param string                                                        $external_id
     * @param int                                                           $amount
     * @param \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\License $license
     */
    public function __construct($external_id, $amount, License $license)
    {
        $this->external_id = $external_id;
        $this->amount = $amount;
        $this->license = $license;
    }

    /**
     * @param array<string, string> $product_data
     *
     * @return \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Product
     */
    public static function fromArray(array $product_data)
    {
        $external_id = null;
        if (isset($product_data['external_id'])) {
            $external_id = (string) $product_data['external_id'];
        }

        $amount = 1;
        if (isset($product_data['amount'])) {
            $amount = (int) $product_data['amount'];
        }

        $license = null;
        if (isset($product_data['license'])) {
            $license = $product_data['license'];
            if (!$license instanceof License) {
                $license = License::fromArray($product_data['license']);
            }
        }

        return new self($external_id, $amount, $license);
    }

    public function toArray()
    {
        return [
            'external_id' => $this->external_id,
            'amount'      => $this->amount,
            'license'     => $this->license->toArray(),
        ];
    }
}