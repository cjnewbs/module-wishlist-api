<?php
declare(strict_types=1);

namespace Newbury\WishlistApi\Api\Data;

interface RequestInterface extends \Magento\Framework\Api\CustomAttributesDataInterface
{
    /**
     * @param int $product
     * @return mixed
     */
    public function setProduct(int $product);

    /**
     * @return int
     */
    public function getProduct(): int;

    /**
     * @param float $qty
     * @return mixed
     */
    public function setQty(float $qty);

    /**
     * @return float
     */
    public function getQty(): float;
}
