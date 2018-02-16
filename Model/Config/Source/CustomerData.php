<?php

namespace Veni\RegisteredCustomersReport\Model\Config\Source;


class CustomerData implements \Magento\Framework\Option\ArrayInterface
{


    public function toOptionArray()
    {
        return [
            ['value' => 'firstname', 'label' => __('First name')],
            ['value' => 'middlename', 'label' => __('Middle name')],
            ['value' => 'lastname', 'label' => __('Last name')],
            ['value' => 'gender', 'label' => __('Gender')],
            ['value' => 'email', 'label' => __('Email')],
            ['value' => 'address', 'label' => __('Address')],
            ['value' => 'telephone', 'label' => __('Phone')],
            ['value' => 'store_id', 'label' => __('Store')],
            ['value' => 'failures_num', 'label' => __('Failures num')],
        ];
    }


}