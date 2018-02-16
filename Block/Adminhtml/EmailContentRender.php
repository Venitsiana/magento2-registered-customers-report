<?php

namespace Veni\RegisteredCustomersReport\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class EmailContentRender extends \Magento\Framework\View\Element\Template
{

    /**
     * @var ResourceConnection
     */
    private $resource;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var \Veni\RegisteredCustomersReport\Helper\DatabaseCustomerManager
     */
    private $customerManager;

    public function __construct(
        Template\Context $context,
        \Veni\RegisteredCustomersReport\Helper\CustomerManager $customerManager,
        ResourceConnection $resource,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->resource = $resource;
        $this->logger = $logger;
        $this->customerManager = $customerManager;
    }

    public function getCustomersData()
    {
        //$this->logger->debug('$this->customerManager->test()');
        //$this->logger->debug(json_encode($this->customerManager->test()));

        return $this->customerManager->findLastRegisteredCustomers();
    }

}