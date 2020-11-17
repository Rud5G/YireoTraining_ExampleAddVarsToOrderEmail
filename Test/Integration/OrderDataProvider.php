<?php declare(strict_types=1);

namespace YireoTraining\ExampleAddVarsToOrderEmail\Test\Integration;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\ObjectManager;

class OrderDataProvider
{
    /**
     * @return OrderInterface
     * @throws LocalizedException
     */
    public static function getOrder(): OrderInterface
    {
        static $order = null;
        if ($order instanceof OrderInterface) {
            return $order;
        }

        /** @var OrderRepositoryInterface $orderRepository */
        $orderRepository = ObjectManager::getInstance()->get(OrderRepositoryInterface::class);

        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = ObjectManager::getInstance()->create(SearchCriteriaBuilder::class);
        $searchCriteria = $searchCriteriaBuilder->create();
        $searchResults = $orderRepository->getList($searchCriteria);
        $items = $searchResults->getItems();

        if (count($items) === 0) {
            throw new NotFoundException(__('No orders found'));
        }

        $order = array_shift($items);
        $payment = $order->getPayment();
        $payment->setAdditionalInformation('foo', 'bar');
        $order->setPayment($payment);

        return $order;
    }
}
