<?php
declare(strict_types=1);

namespace Newbury\WishlistApi\Api;

interface WishlistManagementInterface
{
    /**
     * @param int $customerId
     * @return \Newbury\WishlistApi\Api\Data\WishlistInterface
     */
    public function get(int $customerId);

    /**
     * @param int $customerId
     * @param \Newbury\WishlistApi\Api\Data\RequestInterface $item
     * @return \Newbury\WishlistApi\Api\Data\WishlistInterface
     */
    public function add(int $customerId, $item);

    /**
     * @param int $customerId
     * @param int $itemId
     * @return bool
     */
    public function delete(int $customerId, int $itemId): bool;
}
