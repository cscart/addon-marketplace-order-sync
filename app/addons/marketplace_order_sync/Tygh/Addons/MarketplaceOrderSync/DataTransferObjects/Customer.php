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

final class Customer implements Arrayable
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $phone_number;

    public function __construct($email, $first_name, $last_name, $phone_number = '')
    {
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone_number = $phone_number;
    }

    /**
     * @param array<string, string> $customer_data
     *
     * @return \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Customer
     */
    public static function fromArray(array $customer_data)
    {
        $email = null;
        if (isset($customer_data['email'])) {
            $email = (string) $customer_data['email'];
        }

        $first_name = null;
        if (isset($customer_data['first_name'])) {
            $first_name = (string) $customer_data['first_name'];
        }
        $last_name = null;
        if (isset($customer_data['last_name'])) {
            $last_name = (string) $customer_data['last_name'];
        }

        $phone_number = '';
        if (isset($customer_data['phone_number'])) {
            $phone_number = (string) $customer_data['phone_number'];
        }

        return new self($email, $first_name, $last_name, $phone_number);
    }

    public function toArray()
    {
        return [
            'email'        => $this->email,
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'phone_number' => $this->phone_number,
        ];
    }
}