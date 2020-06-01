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

namespace Tygh\Addons\MarketplaceOrderSync\Collections;

use Illuminate\Support\Collection;
use Tygh\Addons\MarketplaceOrderSync\DataTransferObjects\Product;
use Tygh\Exceptions\DeveloperException;

/**
 * Class ProductCollection
 *
 * @package Tygh\Addons\MarketplaceOrderSync\Collections
 */
final class ProductCollection extends Collection
{
    public function __construct($items = [])
    {
        $this->checkItemsType($items);

        parent::__construct($items);
    }

    protected function checkItemType($item)
    {
        if (!$item instanceof Product) {
            throw new DeveloperException(
                'Incompatible type: ProductCollection can store ' . Product::class . ' instances only'
            );
        }
    }

    protected function checkItemsType($items = [])
    {
        foreach ($items as $item) {
            $this->checkItemType($item);
        }
    }

    public function merge($items)
    {
        $this->checkItemsType($items);

        return parent::merge($items);
    }

    public function union($items)
    {
        $this->checkItemsType($items);

        return parent::union($items);
    }

    public function combine($values)
    {
        $this->checkItemsType($values);

        return parent::combine($values);
    }

    public function prepend($value, $key = null)
    {
        $this->checkItemType($value);

        return parent::prepend($value, $key);
    }

    public function offsetSet($key, $value)
    {
        $this->checkItemType($value);

        parent::offsetSet($key, $value);
    }
}