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

namespace MSP\ReCaptcha\Block;

use Magento\Framework\View\Element\Template;

class Config extends Template
{
    /**
     * @var \MSP\ReCaptcha\Model\Config
     */
    private $config;

    /**
     * @var array
     */
    private $data;

    public function __construct(
        Template\Context $context,
        \MSP\ReCaptcha\Model\Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->data = $data;
    }

    /**
     * Return captcha config for frontend
     * @return array
     */
    public function getCaptchaConfig()
    {
        return [
            'siteKey' => $this->config->getPublicKey(),
            'enabled' => [
                'login' => $this->config->getEnabledFrontendLogin(),
                'create' => $this->config->getEnabledFrontendCreate(),
                'forgot' => $this->config->getEnabledFrontendForgot(),
                'contact' => $this->config->getEnabledFrontendContact(),
            ]
        ];
    }
}
