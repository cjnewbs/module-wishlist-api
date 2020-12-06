<?php
declare(strict_types=1);

namespace Newbury\WishlistApi\Api\Data;

interface WishlistInterface
{
    /**
     * @return int
     */
    public function getWishlistId(): int;

    /**
     * @return int
     */
    public function getCustomerId(): int;

    /**
     * @return bool
     */
    public function getIsShared(): bool;

    /**
     * @return string
     */
    public function getSharingCode(): string;

    /**
     * @return string
     */
    public function geUpdatedAt(): string;

    /**
     * @return \Newbury\WishlistApi\Api\Data\WishlistItemInterface[]
     */
    public function getItems(): array;
}
