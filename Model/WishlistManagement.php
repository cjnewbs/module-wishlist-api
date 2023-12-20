<?php
declare(strict_types=1);

namespace Newbury\WishlistApi\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Wishlist\Model\WishlistFactory;
use Newbury\WishlistApi\Api\WishlistManagementInterface;

class WishlistManagement implements WishlistManagementInterface
{
    /** @var WishlistFactory */
    private $wishlistFactory;
    /** @var ProductRepositoryInterface */
    private $productRepository;
    /** @var ManagerInterface */
    private $eventManager;

    /**
     * WishlistManagement constructor.
     * @param WishlistFactory $wishlistFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ManagerInterface $eventManager
     */
    public function __construct(
        WishlistFactory $wishlistFactory,
        ProductRepositoryInterface $productRepository,
        ManagerInterface $eventManager
    ) {
        $this->wishlistFactory = $wishlistFactory;
        $this->productRepository = $productRepository;
        $this->eventManager = $eventManager;
    }

    /**
     * @param int $customerId
     * @return \Magento\Wishlist\Model\Wishlist|\Newbury\WishlistApi\Api\Data\WishlistInterface
     * @throws NoSuchEntityException
     */
    public function get(int $customerId)
    {
        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId);
        if (!$wishlist->getId()) {
            throw new NoSuchEntityException(__('Customer does not yet have a wishlist', null, 1));
        }
        $wishlist['items'] = $wishlist->getItemCollection()->getItems();
        return $wishlist;
    }

    /**
     * @param int $customerId
     * @param \Newbury\WishlistApi\Api\Data\RequestInterface $item
     * @return \Magento\Wishlist\Model\Wishlist|\Newbury\WishlistApi\Api\Data\WishlistInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function add(int $customerId, $item)
    {
        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId, true);
        $product = $this->productRepository->getById($item->getProduct());

        if (!$product->isVisibleInCatalog()) {
            throw new LocalizedException(__("Sorry, this item can't be added to wishlists"), null, 1);
        }
        $buyRequest = new DataObject();
        $customAttributes = $item->getCustomAttributes();
        if ($customAttributes) {
            $superAttributes = [];
            $bundleOptionQtys = [];
            $bundleOptions = [];
            foreach ($customAttributes as $customAttribute) {
                if (strpos($customAttribute->getAttributeCode(), 'super_attribute_') === 0) {
                    $superAttributeId = str_replace('super_attribute_', '', $customAttribute->getAttributeCode());
                    $superAttributes[$superAttributeId] = $customAttribute->getValue();
                    continue;
                }
                if (strpos($customAttribute->getAttributeCode(), 'bundle_option_qty_') === 0) {
                    $bundleOptionQty = str_replace('bundle_option_qty_', '', $customAttribute->getAttributeCode());
                    $bundleOptionQtys[$bundleOptionQty] = $customAttribute->getValue();
                    continue;
                }
                if (strpos($customAttribute->getAttributeCode(), 'bundle_option_') === 0) {
                    $bundleOption = str_replace('bundle_option_', '', $customAttribute->getAttributeCode());
                    $bundleOption = explode('_', $bundleOption);
                    if (count($bundleOption) === 1) {
                        $bundleOptions[$bundleOption[0]] = $customAttribute->getValue();
                    } elseif (count($bundleOption) === 2) {
                        $bundleOptions[$bundleOption[0]][$bundleOption[1]] = $customAttribute->getValue();
                    }
                    continue;
                }
            }
            if ($superAttributes) {
                $buyRequest->setData('super_attribute', $superAttributes);
            }
            if ($bundleOptionQtys) {
                $buyRequest->setData('bundle_option_qty', $bundleOptionQtys);
            }
            if ($bundleOptions) {
                $buyRequest->setData('bundle_option', $bundleOptions);
            }
        }
        $result = $wishlist->addNewItem($product, $buyRequest);
        if (is_string($result)) {
            throw new LocalizedException(__($result), null, 2);
        }
        if ($wishlist->isObjectNew()) {
            $wishlist->save();
        }
        $this->eventManager->dispatch(
            'wishlist_add_product',
            ['wishlist' => $wishlist, 'product' => $product, 'item' => $result]
        );
        $wishlist['items'] = $wishlist->getItemCollection()->getItems();
        return $wishlist;
    }

    /**
     * @param int $customerId
     * @param int $itemId
     * @return bool
     * @throws \Exception
     */
    public function delete(int $customerId, int $itemId): bool
    {
        $wishlist = $this->wishlistFactory->create()->loadByCustomerId($customerId);
        $item = $wishlist->getItem($itemId);
        if (!$item) {
            throw new NoSuchEntityException(__('No item with ID %1', $itemId), null, 1);
        }
        $item->delete();
        return true;
    }
}
