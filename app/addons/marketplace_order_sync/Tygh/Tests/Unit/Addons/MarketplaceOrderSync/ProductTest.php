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

use DateTimeImmutable;
use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\License;
use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Product;
use Tygh\Tests\Unit\ATestCase;
use TypeError;

class ProductTest extends ATestCase
{
    /**
     * @dataProvider dpFromArray
     */
    public function testFromArray(array $data, Product $expected)
    {
        $actual = Product::fromArray($data);
        $this->assertEquals($expected, $actual);
    }

    public function testFromArrayError()
    {
        $this->expectException(TypeError::class);
        Product::fromArray([]);
    }

    public function testToArray()
    {
        $product = new Product('foo', 1, new License(new DateTimeImmutable('2020-01-01 00:00:00+00:00')));
        $this->assertEquals(
            [
                'external_id' => 'foo',
                'amount'      => 1,
                'license'     => [
                    'expires_on'     => '2020-01-01',
                    'domain'         => '',
                    'license_number' => '',
                ],
            ],
            $product->toArray()
        );
    }

    public function dpFromArray()
    {
        return [
            [
                [
                    'external_id' => 'foo',
                    'license'     => [
                        'expires_on' => '2020-01-01',
                    ],
                ],
                new Product('foo', 1, new License(new DateTimeImmutable('2020-01-01 00:00:00'))),
            ],
            [
                [
                    'external_id' => 'foo',
                    'amount'      => 4,
                    'license'     => [
                        'expires_on' => '2020-01-01',
                    ],
                ],
                new Product('foo', 4, new License(new DateTimeImmutable('2020-01-01 00:00:00'))),
            ],
        ];
    }
}