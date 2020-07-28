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
use Tygh\Addons\MarketplaceOrderSync\Collections\ProductCollection;
use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\License;
use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Product;
use Tygh\Exceptions\DeveloperException;
use Tygh\Tests\Unit\ATestCase;

class ProductCollectionTest extends ATestCase
{
    public function testCreateWrongType()
    {
        $this->expectException(DeveloperException::class);
        new ProductCollection(['foo']);
    }

    public function testCreateCorrectType()
    {
        new ProductCollection([new Product('foo', 1, new License(new DateTimeImmutable()))]);
        $this->assertTrue(true);
    }

    public function testAppendWrongType()
    {
        $this->expectException(DeveloperException::class);
        $collection = new ProductCollection();
        $collection[] = 'foo';
    }

    public function testAppendCorrectType()
    {
        $collection = new ProductCollection();
        $collection[] = new Product('foo', 1, new License(new DateTimeImmutable()));

        $this->assertTrue(true);
    }

    public function testToArray()
    {
        $collection = new ProductCollection(
            [
                new Product('foo', 1, new License(new DateTimeImmutable('2020-01-01 00:00:00+00:00'))),
                new Product('bar', 2, new License(new DateTimeImmutable('2020-01-01 00:00:00+00:00'), 'example.com')),
            ]
        );

        $this->assertEquals(
            [
                [
                    'external_id' => 'foo',
                    'amount'      => 1,
                    'license'     => [
                        'expires_on'     => '2020-01-01',
                        'domain'         => '',
                        'license_number' => '',
                    ],
                ],
                [
                    'external_id' => 'bar',
                    'amount'      => 2,
                    'license'     => [
                        'expires_on'     => '2020-01-01',
                        'domain'         => 'example.com',
                        'license_number' => '',
                    ],
                ],
            ],
            $collection->toArray()
        );
    }
}