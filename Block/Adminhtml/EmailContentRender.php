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
     * @var \Veni\RegisteredCustomersReport\Helper\CustomerManager
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
        return $this->customerManager->test();
//        $connection = $this->resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
//        $select = $connection->describeTable('customer_entity');
//        //$this->logger->debug(json_encode(array_keys($select)));
//        $this->logger->debug('veni');
        return false;
    }

}