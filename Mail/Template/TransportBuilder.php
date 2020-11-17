<?php declare(strict_types=1);

namespace YireoTraining\ExampleAddVarsToOrderEmail\Mail\Template;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\EmailMessageInterface;
use Magento\Framework\Mail\MailMessageInterface;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\Template\TransportBuilder as OriginalTransportBuilder;

class TransportBuilder extends OriginalTransportBuilder
{
    /**
     * @return MessageInterface|MailMessageInterface|EmailMessageInterface
     * @throws LocalizedException
     */
    public function getMessage()
    {
        $this->prepareMessage();
        return $this->message;
    }
}
