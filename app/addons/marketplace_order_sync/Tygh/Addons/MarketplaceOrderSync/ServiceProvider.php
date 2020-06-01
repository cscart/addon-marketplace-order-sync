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

namespace Tygh\Addons\MarketplaceOrderSync;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tygh\Http;
use Tygh\Registry;
use Tygh\Tygh;

final class ServiceProvider implements ServiceProviderInterface
{
    const API_VERSION = '2.1';

    /**
     * @return string
     */
    private static function getApiUri()
    {
        $api_uri = Registry::get('addons.marketplace_order_sync.api_uri');
        if (!$api_uri) {
            $api_uri = Registry::get('config.resources.marketplace_url');
        }

        return rtrim($api_uri, '/');
    }

    /**
     * @return array<string, string>
     */
    private static function getAuthDetails()
    {
        $auth_details = [
            'login' => null,
            'key' => null,
        ];

        return array_merge($auth_details, Registry::ifGet('addons.marketplace_order_sync', []));
    }

    /**
     * Provides service to register orders in Marketplace.
     *
     * @return \Tygh\Addons\MarketplaceOrderSync\Service
     */
    public static function getService()
    {
        return Tygh::$app['addons.marketplace_order_sync.service'];
    }

    public function register(Container $app)
    {
        $app['addons.marketplace_order_sync.service'] = function () {
            return new Service(
                new Http(),
                self::getAuthDetails(),
                self::getApiUri(),
                self::API_VERSION
            );
        };
    }
}