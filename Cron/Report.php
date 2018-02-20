<?php

namespace Veni\RegisteredCustomersReport\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

class Report
{

    /**
     * @var \Veni\RegisteredCustomersReport\Helper\Email
     */
    protected $helperEmail;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(\Veni\RegisteredCustomersReport\Helper\Email $helperEmail,
                                ScopeConfigInterface $scopeConfig,
                                LoggerInterface $logger)
    {
        $this->helperEmail = $helperEmail;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {

        /* Receiver Detail  */
        $receiverInfo = [
            'name' => $this->getReceiverData()['name'],
            'email' => $this->getReceiverData()['email']
        ];

        /* Sender Detail  */
        $senderInfo = [
            'name' => $this->getSenderData()['name'],
            'email' => $this->getSenderData()['email'],
        ];
        $this->logger->debug('sender data Email '.$this->getSenderData()['name'].': '. $this->getSenderData()['email']);
        $this->logger->debug('receiver data Email '.$this->getReceiverData()['name'].': '. $this->getReceiverData()['email']);

        // Assign values for your template variables
        $emailTemplateVariables = [];

        /* call send mail method from helper or where you define it*/
        $this->helperEmail->sendEmail(
            $emailTemplateVariables,
            $senderInfo,
            $receiverInfo
        );

    }

    /**
     * Return store configuration value of sales email
     * @return string
     */
    private function getSalesEmail()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return store configuration value of sales email name
     * @return string
     */
    private function getSalesName()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    private function getRegisteredCustomersEmailData(string $value)
    {
        return $this->scopeConfig->getValue(
            'registered_customers/emails/'.$value,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    private function getSenderData() {

        $data = [];
        if($this->getRegisteredCustomersEmailData('sender_email')) {
            $data['email'] = $this->getRegisteredCustomersEmailData('sender_email');
        }
        else {
            $data['email'] = $this->getSalesEmail();
        }

        if($this->getRegisteredCustomersEmailData('sender_name')) {
            $data['name'] = $this->getRegisteredCustomersEmailData('sender_name');
        }
        else {
            $data['name'] = $this->getSalesName();
        }

        return $data;
    }

    private function getReceiverData() {

        $data = [];
        if($this->getRegisteredCustomersEmailData('receiver_email')) {
            $data['email'] = $this->getRegisteredCustomersEmailData('receiver_email');
        }
        else {
            $data['email'] = $this->getSalesEmail();
        }

        if($this->getRegisteredCustomersEmailData('receiver_name')) {
            $data['name'] = $this->getRegisteredCustomersEmailData('receiver_name');
        }
        else {
            $data['name'] = $this->getSalesName();
        }

        return $data;
    }

}