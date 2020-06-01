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

use DateTimeImmutable;
use Illuminate\Contracts\Support\Arrayable;

final class License implements Arrayable
{
    /**
     * @var \DateTimeImmutable
     */
    public $expires_on;

    /**
     * @var string
     */
    public $domain;

    /**
     * License constructor.
     *
     * @param \DateTimeImmutable $expires_on
     * @param string             $domain
     */
    public function __construct(DateTimeImmutable $expires_on, $domain = '')
    {
        $this->expires_on = $expires_on;
        $this->domain = $domain;
    }

    /**
     * @param array<string, string> $license_data
     *
     * @return \Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\License
     */
    public static function fromArray(array $license_data)
    {
        $expires_on = null;
        if (isset($license_data['expires_on']) && !$expires_on instanceof DateTimeImmutable) {
            $expires_on = new DateTimeImmutable($license_data['expires_on']);
        }

        $domain = '';
        if (isset($license_data['domain'])) {
            $domain = $license_data['domain'];
        }

        return new self($expires_on, $domain);
    }

    public function toArray()
    {
        return [
            'expires_on' => $this->expires_on->format('Y-m-d'),
            'domain'     => $this->domain,
        ];
    }
}