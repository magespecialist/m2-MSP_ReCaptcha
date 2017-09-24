<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @category   MSP
 * @package    MSP_ReCaptcha
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\ReCaptcha\Model\Config\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    const TYPE_RECAPTCHA = 'recaptcha';
    const TYPE_INVISIBLE = 'invisible';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'recaptcha', 'label' => __('reCaptcha v2')],
            ['value' => 'invisible', 'label' => __('Invisible reCaptcha')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $options = $this->toOptionArray();
        $return = [];

        foreach ($options as $option) {
            $return[$option['value']] = $option['label'];
        }

        return $return;
    }
}
