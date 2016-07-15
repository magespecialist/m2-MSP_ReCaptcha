<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@idealiagroup.com so we can send you a copy immediately.
 *
 * @category   MSP
 * @package    MSP_ReCaptcha
 * @copyright  Copyright (c) 2016 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\ReCaptcha\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    const XML_PATH_GENERAL_ENABLED_BACKEND = 'msp_securitysuite/recaptcha/enabled_backend';
    const XML_PATH_GENERAL_ENABLED_FRONTEND = 'msp_securitysuite/recaptcha/enabled_frontend';
    const XML_PATH_GENERAL_PUBLIC_KEY = 'msp_securitysuite/recaptcha/public_key';
    const XML_PATH_GENERAL_PRIVATE_KEY = 'msp_securitysuite/recaptcha/private_key';

    protected $scopeConfigInterface;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfigInterface
    ) {
        $this->scopeConfigInterface = $scopeConfigInterface;

        parent::__construct($context);
    }

    /**
     * Get error
     * @return string
     */
    public function getErrorDescription()
    {
        return __('Incorrect reCAPTCHA');
    }

    /**
     * Get google recaptcha public key
     * @return string
     */
    public function getPublicKey()
    {
        return trim($this->scopeConfigInterface->getValue(self::XML_PATH_GENERAL_PUBLIC_KEY));
    }

    /**
     * Get google recaptcha private key
     * @return string
     */
    public function getPrivateKey()
    {
        return trim($this->scopeConfigInterface->getValue(self::XML_PATH_GENERAL_PRIVATE_KEY));
    }

    /**
     * Return true if enabled on backend
     * @return bool
     */
    public function getEnabledBackend()
    {
        if (!$this->getPrivateKey() || !$this->getPublicKey()) {
            return false;
        }

        return (bool) $this->scopeConfigInterface->getValue(self::XML_PATH_GENERAL_ENABLED_BACKEND);
    }

    /**
     * Return true if enabled on frontend
     * @return bool
     */
    public function getEnabledFrontend()
    {
        if (!$this->getPrivateKey() || !$this->getPublicKey()) {
            return false;
        }

        return (bool) $this->scopeConfigInterface->getValue(self::XML_PATH_GENERAL_ENABLED_FRONTEND);
    }
}
