<?php declare(strict_types=1);

namespace YireoTraining\ExampleAddVarsToOrderEmail\Test\Integration;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use YireoTraining\ExampleAddVarsToOrderEmail\Mail\Template\TransportBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Store\Model\Store;
use Magento\TestFramework\ObjectManager;
use PHPUnit\Framework\TestCase;

class SendEmailTest extends TestCase
{
    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testIfEmailMessageContainsRightValues()
    {
        $order = OrderDataProvider::getOrder();
        $this->assertEquals('bar', $order->getPayment()->getAdditionalInformation()['foo']);

        $templateVars = new DataObject();
        $templateVars->setData('order', $order);
        $templateVars->setData('some', 'value');

        $message = $this->getTransportBuilder()
            ->setTemplateIdentifier('dummy')
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars($templateVars->getData())
            ->addTo('info@example.org', 'Example')
            ->getMessage();

        $content = quoted_printable_decode($message->getBodyText());

        $this->assertContentContains($content, 'dummy', 'Class "dummy" not found');
        $this->assertContentContains($content, 'foo = bar', 'Value "bar" not found');
    }

    /**
     * @param string $content
     * @param string $needle
     * @param string $message
     */
    private function assertContentContains(string $content, string $needle, string $message = '')
    {
        $this->assertTrue((bool)strstr($content, $needle), $message . ': ' . $content);
    }

    /**
     * @return TransportBuilder
     */
    private function getTransportBuilder(): TransportBuilder
    {
        return ObjectManager::getInstance()->get(TransportBuilder::class);
    }
}
