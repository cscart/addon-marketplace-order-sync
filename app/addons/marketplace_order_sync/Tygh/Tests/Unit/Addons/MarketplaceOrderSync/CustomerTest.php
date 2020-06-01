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

namespace Tygh\Tests\Unit\Addons\MarketplaceOrderSync;

use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Customer;
use Tygh\Tests\Unit\ATestCase;

class CustomerTest extends ATestCase
{
    /**
     * @dataProvider dpTestFromArray
     */
    public function testFromArray(array $data, Customer $expected)
    {
        $actual = Customer::fromArray($data);
        $this->assertEquals($expected, $actual);
    }

    public function dpTestFromArray()
    {
        return [
            [
                [
                    'email'      => 'foo@example.com',
                    'first_name' => 'First',
                    'last_name'  => 'Last',
                ],
                new Customer('foo@example.com', 'First', 'Last', ''),
            ],
            [
                [
                    'email'        => 'foo@example.com',
                    'first_name'   => 'First',
                    'last_name'    => 'Last',
                    'phone_number' => '+79999999999',
                ],
                new Customer('foo@example.com', 'First', 'Last', '+79999999999'),
            ],
        ];
    }
}