<?php declare(strict_types=1);

namespace YireoTraining\ExampleAddVarsToOrderEmail\Plugin;

use Magento\Sales\Api\Data\OrderPaymentInterface;

class AddDummyDataToOrderPayment
{
    /**
     * @param OrderPaymentInterface $orderPayment
     * @param array $additionalInformation
     * @return array
     */
    public function afterGetAdditionalInformation(
        OrderPaymentInterface $orderPayment,
        $additionalInformation = []
    ): array {
        $additionalInformation['foo'] = 'bar';
        return $additionalInformation;
    }
}
