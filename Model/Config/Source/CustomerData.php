<?php

namespace Veni\RegisteredCustomersReport\Model\Config\Source;


class CustomerData implements \Magento\Framework\Option\ArrayInterface
{


    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('First name')],
            ['value' => 1, 'label' => __('Middle name')],
            ['value' => 2, 'label' => __('Last name')],
            ['value' => 3, 'label' => __('Gender')],
            ['value' => 4, 'label' => __('Email')],
            ['value' => 5, 'label' => __('Address')],
            ['value' => 6, 'label' => __('Phone')],
            ['value' => 7, 'label' => __('Store')],
            ['value' => 8, 'label' => __('Failures num')],
        ];
    }


}