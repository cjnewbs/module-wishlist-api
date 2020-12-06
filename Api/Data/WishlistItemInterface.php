<?php
declare(strict_types=1);

namespace Newbury\WishlistApi\Api\Data;

interface WishlistItemInterface
{
    /**
     * @return int
     */
    public function getWishlistItemId(): int;

    /**
     * @return int
     */
    public function getWishlistId(): int;

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @return string
     */
    public function getAddedAt(): string;

    /**
     * @return float
     */
    public function getQty(): float;

    /**
     * @return string
     */
    public function getProductName(): string;

    /**
     * @return float
     */
    public function getPrice(): float;
}
