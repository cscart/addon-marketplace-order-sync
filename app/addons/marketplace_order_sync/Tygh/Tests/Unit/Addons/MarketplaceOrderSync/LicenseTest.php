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
use Tygh\Tests\Unit\ATestCase;
use TypeError;

class LicenseTest extends ATestCase
{
    /**
     * @dataProvider dpTestFromArray
     */
    public function testFromArray(array $data, License $expected)
    {
        $actual = License::fromArray($data);
        $this->assertEquals($expected, $actual);
    }

    public function testFromArrayError()
    {
        $this->expectException(TypeError::class);
        License::fromArray([]);
    }

    public function dpTestFromArray()
    {
        return [
            [
                [
                    'expires_on' => '2020-01-01',
                ],
                new License(new DateTimeImmutable('2020-01-01 00:00:00+00:00')),
            ],
            [
                [
                    'expires_on' => '2020-01-01',
                    'domain'     => 'example.com',
                ],
                new License(new DateTimeImmutable('2020-01-01 00:00:00+00:00'), 'example.com'),
            ],
        ];
    }
}