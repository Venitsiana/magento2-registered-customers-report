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
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Template\Context $context,
        \Veni\RegisteredCustomersReport\Helper\CustomerManager $customerManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ResourceConnection $resource,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->resource = $resource;
        $this->logger = $logger;
        $this->customerManager = $customerManager;
        $this->storeManager = $storeManager;
    }

    public function getCustomersData()
    {
        return $this->customerManager->findLastRegisteredCustomers();
    }

    public function getStoreName($storeId)
    {
        return $this->storeManager->getStore($storeId)->getName();
    }

}