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

namespace MSP\ReCaptcha\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MSP\ReCaptcha\Api\ValidateInterface;
use Magento\Framework\Exception\Plugin\AuthenticationException;
use MSP\ReCaptcha\Helper\Data;

class LoginObserver implements ObserverInterface
{
    /**
     * @var ValidateInterface
     */
    private $validate;

    /**
     * @var Data
     */
    private $helperData;

    public function __construct(
        ValidateInterface $validate,
        Data $helperData
    ) {
        $this->validate = $validate;
        $this->helperData = $helperData;
    }

    public function execute(Observer $observer)
    {
        if (!$this->helperData->getEnabledBackend()) {
            return;
        }

        if (!$this->validate->validate()) {
            throw new AuthenticationException(__('Incorrect reCaptcha'));
        }
    }
}
