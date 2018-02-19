<?php

namespace Veni\RegisteredCustomersReport\Model\Config\Source;


class CustomerData implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Veni\RegisteredCustomersReport\Helper\DatabaseCustomerManager
     */
    protected $customerManager;

    public function __construct(\Veni\RegisteredCustomersReport\Helper\DatabaseCustomerManager $customerManager)
    {
        $this->customerManager = $customerManager;
    }

    public function toOptionArray()
    {
        $customerData = $this->customerManager->getCustomerDataLabels();
        $options = [];

        foreach ($customerData as $key => $value) {
            $options[] = ['value' => $key, 'label' => $value];
        }

        return $options;
    }

}
