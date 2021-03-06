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
            ->columns($this->getDbColumns('customer_entity'))
            ->joinLeft(
                ['st' => $this->collection->getTable('store')],
                'st.store_id = e.store_id',
                $this->getDbColumns('store'))
            ->joinLeft(
                ['second_table' => $this->collection->getTable('customer_address_entity')],
                'second_table.entity_id = e.default_shipping',
                $this->getDbColumns('customer_address_entity'));

        $this->logger->debug($this->collection->getSelect());
        return $this->collection;
    }

    public function findLastRegisteredCustomers()
    {

        // @todo set time config
        $customers = $this->getCustomerCollection()->load();

        $labels = $this->getCustomerDataConfigLabels();

        return compact('customers', 'labels');
    }

    private function getDbColumns($dbTableName)
    {
        $customerConfigValues = $this->getCustomerDataConfigValues();
        $fieldsMap = $this->fieldsMap($dbTableName);

        $selectedColumns = [];
        foreach ($customerConfigValues as $value) {
            if(array_key_exists($value, $fieldsMap)) {
                $selectedColumns[] = $fieldsMap[$value];
            }
        }

        return $selectedColumns;
    }

    private function fieldsMap($dbTableName) {

        switch ($dbTableName) {
            case 'customer_entity':
                return [
                    'firstname' => 'firstname',
                    'middlename' => 'middlename',
                    'lastname' => 'lastname',
                    'gender' => 'gender',
                    'email' => 'email',
                    'failures_num' => 'failures_num',
                ];
            case 'customer_address_entity':
                return [
                    'city' => 'city',
                    'street' => 'street',
                    'postcode' => 'postcode',
                    'address' => 'CONCAT( postcode," ", city, " ", street ) AS address',
                    'telephone' => 'telephone',
                ];
            case 'store':
                return [
                    'store_id' => 'name',
                ];
        }

    }

    public function getCustomerDataLabels()
    {

        return [
            'firstname' => __('First name'),
            'middlename' => __('Middle name'),
            'lastname' => __('Last name'),
            'gender' => __('Gender'),
            'email' => __('Email'),
            'failures_num' => __('Failures num'),
            'store_id' => __('Store'),
            'address' => __('Address'),
            'telephone' => __('Phone'),
        ];
    }

    private function getConfigValue(string $key, $storeId = null)
    {
        $value = $this->config->getValue(
            'registered_customers/general/' . $key,
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

    private function getAddressFields()
    {
        return [
            'city', 'street', 'postcode'
        ];
    }

}