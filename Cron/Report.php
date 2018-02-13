<?php

namespace Veni\RegisteredCustomersReport\Cron;

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

    public function __construct(\Veni\RegisteredCustomersReport\Helper\Email $helperEmail,
                                LoggerInterface $logger)
    {
        $this->helperEmail = $helperEmail;
        $this->logger = $logger;
    }

    public function execute()
    {

        /* Receiver Detail  */
        $receiverInfo = [
            'name' => 'Reciver Name',
            'email' => 'veilieva@westum.com'
        ];

        /* Sender Detail  */
        $senderInfo = [
            'name' => 'Sender Name',
            'email' => 'veilieva@westum.com',
        ];
        $this->logger->debug('before send Email');

        // Assign values for your template variables
        $emailTemplateVariables = [];
        //die('die die');
        /* call send mail method from helper or where you define it*/
        $this->helperEmail->sendEmail(
            $emailTemplateVariables,
            $senderInfo,
            $receiverInfo
        );

    }

}