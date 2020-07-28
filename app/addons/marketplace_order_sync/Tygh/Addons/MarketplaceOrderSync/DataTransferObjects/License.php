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
     * @var string
     */
    protected $license_number;

    /**
     * License constructor.
     *
     * @param \DateTimeImmutable $expires_on
     * @param string             $domain
     * @param string             $license_number
     */
    public function __construct(DateTimeImmutable $expires_on, $domain = '', $license_number = '')
    {
        $this->expires_on = $expires_on;
        $this->domain = $domain;
        $this->license_number = $license_number;
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

        $license_number = '';
        if (isset($license_data['license_number'])) {
            $license_number = $license_data['license_number'];
        }

        return new self($expires_on, $domain, $license_number);
    }

    public function toArray()
    {
        return [
            'expires_on'     => $this->expires_on->format('Y-m-d'),
            'domain'         => $this->domain,
            'license_number' => $this->license_number,
        ];
    }
}