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

use Tygh\Addons\MarketplaceOrderSync\Service;
use Tygh\Http;
use Tygh\Tests\Unit\ATestCase;

class ServiceTest extends ATestCase
{
    /**
     * @dataProvider dpTestBuildRequestUri
     */
    public function testBuildRequestUri($api_uri, $path, $expected)
    {
        $service = new Service(
            $this->createMock(Http::class),
            [],
            $api_uri,
            '1.0'
        );

        $this->assertEquals($expected, $service->buildRequestUri($path));
    }

    public function dpTestBuildRequestUri()
    {
        return [
            [
                'http://example.org',
                'external_orders',
                'http://example.org/api/1.0/external_orders'
            ],
            [
                'http://example.org/',
                'external_orders',
                'http://example.org/api/1.0/external_orders'
            ],
            [
                'http://example.org/?access_token=111',
                'external_orders',
                'http://example.org/api/1.0/external_orders?access_token=111'
            ]
        ];
    }
}