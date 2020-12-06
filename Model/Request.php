<?php
declare(strict_types=1);

namespace Newbury\WishlistApi\Model;

use Newbury\WishlistApi\Api\Data\RequestInterface;

class Request extends \Magento\Framework\DataObject implements RequestInterface
{
    public function setProduct(int $product)
    {
        return $this->setData('product', $product);
    }

    public function getProduct(): int
    {
        return $this->getData('product');
    }

    public function setQty(float $qty)
    {
        return $this->setData('qty', $qty);
    }

    public function getQty(): float
    {
        return $this->getData('qty');
    }

    public function getCustomAttribute($attributeCode)
    {
        return $this->_data[self::CUSTOM_ATTRIBUTES][$attributeCode] ?? null;
    }

    public function setCustomAttribute($attributeCode, $attributeValue)
    {
        return $this->_data[self::CUSTOM_ATTRIBUTES][$attributeCode] = $attributeValue;
    }

    public function getCustomAttributes()
    {
        return $this->_data[self::CUSTOM_ATTRIBUTES] ?? null;
    }

    public function setCustomAttributes(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {
            $this->setCustomAttribute($attribute, $value);
        }
    }
}
