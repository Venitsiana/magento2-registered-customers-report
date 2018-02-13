<?php

namespace Veni\RegisteredCustomersReport\Block\Adminhtml;

use Magento\Framework\View\Element\Template;


class EmailContentRender extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    private $customerFactory;

    public function __construct(
        Template\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerFactory = $customerFactory;
    }

    public function getCustomersData()
    {
        return false;

        $customerModel = $this->customerFactory->create();
        $customersCollection = $customerModel->getCollection();

        $customersCollection
            ->addAttributeToSelect("*")
            ->addAttributeToFilter("created_at", array("gt" => ""))->load();
    }

}