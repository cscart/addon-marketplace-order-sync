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

use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Order;
use Tygh\Addons\MarketplaceOrderSync\Exceptions\BadRequestException;
use Tygh\Addons\MarketplaceOrderSync\Exceptions\NotAuthorizedException;
use Tygh\Addons\MarketplaceOrderSync\Exceptions\OrderPlacementNotAllowedException;
use Tygh\Addons\MarketplaceOrderSync\Exceptions\ProductNotFoundException;
use Tygh\Api\Response;
use Tygh\Common\OperationResult;
use Tygh\Exceptions\DeveloperException;
use Tygh\Http;

final class Service
{
    /**
     * @var \Tygh\Http
     */
    private $http_client;

    /**
     * @var array
     */
    private $auth_details;

    /**
     * @var string
     */
    private $api_uri;

    /**
     * @var string
     */
    private $api_version;

    /**
     * Service constructor.
     *
     * @param \Tygh\Http            $http_client  HTTP client instance
     * @param array<string, string> $auth_details Marketplace API authentication details: login and key
     * @param string                $api_uri      Marketplace URI
     * @param string                $api_version  API version
     */
    public function __construct(Http $http_client, array $auth_details, $api_uri, $api_version)
    {
        $this->http_client = $http_client;
        $this->auth_details = $auth_details;
        $this->api_uri = $api_uri;
        $this->api_version = $api_version;
    }

    /**
     * Registers order in Marketplace.
     *
     * @param \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Order $order
     *
     * @return int Created order ID
     *
     * @throws \Tygh\Addons\MarketplaceOrderSync\Exceptions\NotAuthorizedException When user is not authorized
     * @throws \Tygh\Addons\MarketplaceOrderSync\Exceptions\BadRequestException When invalid request data passed
     * @throws \Tygh\Addons\MarketplaceOrderSync\Exceptions\OrderPlacementNotAllowedException When order placement is
     *                                                                                        not allowed
     * @throws \Tygh\Addons\MarketplaceOrderSync\Exceptions\ProductNotFoundException When product with the specified ID
     *                                                                               was not found on Marketplace
     * @throws \Tygh\Exceptions\DeveloperException When something goes wrong and it's not listed above
     */
    public function registerOrder(Order $order)
    {
        $request_result = $this->request('external_orders', $order->toArray());
        if (!$request_result->isSuccess()) {
            switch ($request_result->getData('response_http_code')) {
                case Response::STATUS_UNAUTHORIZED:
                    throw new NotAuthorizedException();
                case Response::STATUS_BAD_REQUEST:
                    throw new BadRequestException($request_result->getData('response_body')['message']);
                case Response::STATUS_FORBIDDEN:
                    throw new OrderPlacementNotAllowedException();
                case Response::STATUS_NOT_FOUND:
                    throw new ProductNotFoundException($request_result->getData('response_body')['message']);
                default:
                    throw new DeveloperException(
                        $request_result->getData('response_body_raw'),
                        $request_result->getData('response_http_code')
                    );
            }
        }

        return (int) $request_result->getData('response_body')['order_id'];
    }

    /**
     * @param string                $path
     * @param array<string, string> $data
     *
     * @return \Tygh\Common\OperationResult
     */
    private function request($path, array $data)
    {
        $response_body = $this->http_client->post(
            $this->buildRequestUri($path),
            json_encode($data),
            [
                'headers'    => ['Content-type: application/json'],
                'basic_auth' => [$this->auth_details['login'], $this->auth_details['key']],
            ]
        );

        $response_http_code = $this->http_client->getStatus();

        $result = new OperationResult();
        $result->setSuccess($response_http_code >= 200 && $response_http_code < 300);
        $result->setData($response_body, 'response_body_raw');
        $result->setData(json_decode($response_body, true), 'response_body');
        $result->setData($response_http_code, 'response_http_code');

        return $result;
    }

    public function buildRequestUri($path)
    {
        $api_uri_parts = parse_url($this->api_uri);

        $result = $api_uri_parts['scheme'] . '://';
        $result .= $api_uri_parts['host'];
        if (!empty($api_uri_parts['port'])) {
            $result .= ':' . $api_uri_parts['port'];
        }
        if (!empty($api_uri_parts['path'])) {
            $result .= $api_uri_parts['path'];
        }
        $result = rtrim($result, '/') . '/api/' . $this->api_version . '/' . $path;
        if (!empty($api_uri_parts['query'])) {
            $result .= '?' . $api_uri_parts['query'];
        }

        return $result;
    }
}