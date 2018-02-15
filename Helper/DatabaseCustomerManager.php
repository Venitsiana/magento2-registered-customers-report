<?php

namespace Veni\RegisteredCustomersReport\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

use Psr\Log\LoggerInterface;

class DatabaseCustomerManager implements CustomerManager
{

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\Collection
     */
    protected $collection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $config;

    public function __construct(\Magento\Customer\Model\ResourceModel\Customer\Collection $collection,
                                ScopeConfigInterface $config,
                                LoggerInterface $logger)
    {
        $this->collection = $collection;
        $this->logger = $logger;
        $this->config = $config;
    }

    private function getCustomerCollection()
    {

        $this->collection
            ->getSelect()
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns(['firstname', 'lastname'])
            ->joinLeft(
                ['second_table' => $this->collection->getTable('customer_address_entity')],
                'second_table.entity_id = e.default_shipping',
                ['city', 'telephone']);

        $this->logger->debug($this->collection->getSelect());

        return $this->collection;
    }

    public function findLastRegisteredCustomers()
    {

        $customersCollection = $this->getCustomerCollection();
        $customersCollection
            ->addAttributeToSelect("*")
            ->load();

        return $customersCollection;
    }

    public function getDbColumnNames()
    {

        return [
            0 => 'e.firstname',
            1 => 'e.middlename',
            2 => 'e.lastname',
            3 => 'e.gender',
            4 => 'e.email',
            5 => 'second_table.postcode',
            6 => 'second_table.city',
            7 => 'second_table.telephone',
            8 => 'e.store_id',
            9 => 'e.failures_num',
        ];
    }

    public function getCustomerDataLabels()
    {

        return [
            0 => __('First name'),
            1 => __('Middle name'),
            2 => __('Last name'),
            3 => __('Gender'),
            4 => __('Email'),
            5 => __('Address'),
            6 => __('Phone'),
            7 => __('Store'),
            8 => __('Failures num'),
        ];
    }

    private function getConfigValue(string $key, $storeId = null)
    {
        $value = $this->config->getValue(
            'veni_report/registered_customers/' . $key,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $value;
    }

    private function getCustomerDataConfigValues()
    {
        return explode(',', $this->getConfigValue('data'));
    }

    private function getCustomerDataConfigLabels()
    {
        $customerConfigLabels = [];
        $customerConfigValues = $this->getCustomerDataConfigValues();
        $customerDataLabels = $this->getCustomerDataLabels();

        foreach ( $customerConfigValues as $value ) {
            $customerConfigLabels[] = $customerDataLabels[$value];
        }

        return $customerConfigLabels;
    }

    public function test()
    {
        //return false;
        $customers = $this->findLastRegisteredCustomers();
        $customerConfigValues = $this->getCustomerDataConfigValues();
        $dbColumnNames = $this->getDbColumnNames();

        $newCustomersArray = [];
        foreach ($customers as $customer) {
            foreach ($customerConfigValues as $value) {
                $newCustomersArray['customers'][$customer->getData('entity_id')][] = $customer->getData($dbColumnNames[$value]);
            }
        }
        $newCustomersArray['labels'] = $this->getCustomerDataConfigLabels();
        $this->logger->debug(json_encode($newCustomersArray));
        $this->logger->debug('aideee');

        return $newCustomersArray;
    }

}