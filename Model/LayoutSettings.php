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


class LayoutSettings
{
    /**
     * @var Config
     */
    private $config;

    /**
     * LayoutSettings constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Return captcha config for frontend
     * @return array
     */
    public function getCaptchaSettings()
    {
        return [
            'siteKey' => $this->config->getPublicKey(),
            'size' => $this->config->getFrontendSize(),
            'badge' => $this->config->getFrontendPosition(),
            'theme' => $this->config->getFrontendTheme(),
            'lang' => $this->config->getLanguageCode(),
            'enabled' => [
                'login' => $this->config->isEnabledFrontendLogin(),
                'create' => $this->config->isEnabledFrontendCreate(),
                'forgot' => $this->config->isEnabledFrontendForgot(),
                'contact' => $this->config->isEnabledFrontendContact(),
            ]
        ];
    }
}
