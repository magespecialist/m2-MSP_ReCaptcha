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

namespace MSP\ReCaptcha\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;
use MSP\ReCaptcha\Model\Config\Source\Type;

class Config
{
    const XML_PATH_ENABLED_BACKEND = 'msp_securitysuite_recaptcha/backend/enabled';
    const XML_PATH_ENABLED_FRONTEND = 'msp_securitysuite_recaptcha/frontend/enabled';

    const XML_PATH_TYPE_FRONTEND = 'msp_securitysuite_recaptcha/frontend/type';
    const XML_PATH_LANGUAGE_CODE = 'msp_securitysuite_recaptcha/frontend/lang';

    const XML_PATH_POSITION_FRONTEND = 'msp_securitysuite_recaptcha/frontend/position';

    const XML_PATH_SIZE_BACKEND = 'msp_securitysuite_recaptcha/backend/size';
    const XML_PATH_SIZE_FRONTEND = 'msp_securitysuite_recaptcha/frontend/size';
    const XML_PATH_THEME_BACKEND = 'msp_securitysuite_recaptcha/backend/theme';
    const XML_PATH_THEME_FRONTEND = 'msp_securitysuite_recaptcha/frontend/theme';

    const XML_PATH_PUBLIC_KEY = 'msp_securitysuite_recaptcha/general/public_key';
    const XML_PATH_PRIVATE_KEY = 'msp_securitysuite_recaptcha/general/private_key';

    const XML_PATH_ENABLED_FRONTEND_LOGIN = 'msp_securitysuite_recaptcha/frontend/enabled_login';
    const XML_PATH_ENABLED_FRONTEND_FORGOT = 'msp_securitysuite_recaptcha/frontend/enabled_forgot';
    const XML_PATH_ENABLED_FRONTEND_CONTACT = 'msp_securitysuite_recaptcha/frontend/enabled_contact';
    const XML_PATH_ENABLED_FRONTEND_CREATE = 'msp_securitysuite_recaptcha/frontend/enabled_create';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get error
     * @return Phrase
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
        return trim($this->scopeConfig->getValue(static::XML_PATH_PUBLIC_KEY));
    }

    /**
     * Get google recaptcha private key
     * @return string
     */
    public function getPrivateKey()
    {
        return trim($this->scopeConfig->getValue(static::XML_PATH_PRIVATE_KEY));
    }

    /**
     * Return true if enabled on backend
     * @return bool
     */
    public function isEnabledBackend()
    {
        if (!$this->getPrivateKey() || !$this->getPublicKey()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(static::XML_PATH_ENABLED_BACKEND);
    }

    /**
     * Return true if enabled on frontend
     * @return bool
     */
    public function isEnabledFrontend()
    {
        if (!$this->getPrivateKey() || !$this->getPublicKey()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(static::XML_PATH_ENABLED_FRONTEND);
    }

    /**
     * Return true if enabled on frontend login
     * @return bool
     */
    public function isEnabledFrontendLogin()
    {
        if (!$this->isEnabledFrontend()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(static::XML_PATH_ENABLED_FRONTEND_LOGIN);
    }

    /**
     * Return true if enabled on frontend forgot password
     * @return bool
     */
    public function isEnabledFrontendForgot()
    {
        if (!$this->isEnabledFrontend()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(static::XML_PATH_ENABLED_FRONTEND_FORGOT);
    }

    /**
     * Return true if enabled on frontend contact
     * @return bool
     */
    public function isEnabledFrontendContact()
    {
        if (!$this->isEnabledFrontend()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(static::XML_PATH_ENABLED_FRONTEND_CONTACT);
    }

    /**
     * Return true if enabled on frontend create user
     * @return bool
     */
    public function isEnabledFrontendCreate()
    {
        if (!$this->isEnabledFrontend()) {
            return false;
        }

        return (bool) $this->scopeConfig->getValue(static::XML_PATH_ENABLED_FRONTEND_CREATE);
    }

    /**
     * Get data size
     * @return string
     */
    public function getFrontendSize()
    {
        if ($this->getFrontendType() == Type::TYPE_INVISIBLE) {
            return 'invisible';
        }

        return $this->scopeConfig->getValue(static::XML_PATH_SIZE_FRONTEND);
    }

    /**
     * Get data size
     * @return string
     */
    public function getBackendSize()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_SIZE_BACKEND);
    }

    /**
     * Get data size
     * @return string
     */
    public function getFrontendTheme()
    {
        if ($this->getFrontendType() == Type::TYPE_INVISIBLE) {
            return null;
        }

        return $this->scopeConfig->getValue(static::XML_PATH_THEME_FRONTEND);
    }

    /**
     * Get data size
     * @return string
     */
    public function getBackendTheme()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_THEME_BACKEND);
    }

    /**
     * Get data size
     * @return string
     */
    public function getFrontendPosition()
    {
        if ($this->getFrontendType() != Type::TYPE_INVISIBLE) {
            return null;
        }

        return $this->scopeConfig->getValue(static::XML_PATH_POSITION_FRONTEND);
    }

    /**
     * Get data size
     * @return string
     */
    public function getFrontendType()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_TYPE_FRONTEND);
    }

    /**
     * Get language code
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_LANGUAGE_CODE, ScopeInterface::SCOPE_STORE);
    }
}
