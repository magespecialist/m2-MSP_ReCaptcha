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

namespace MSP\ReCaptcha\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use MSP\ReCaptcha\Model\Config;

class ReCaptcha extends Template
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var Config
     */
    private $config;

    /**
     * ReCaptcha constructor.
     * @param Template\Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->data = $data;
        $this->config = $config;
    }

    /**
     * Get public reCaptcha key
     * @return string
     */
    public function getPublicKey()
    {
        return $this->config->getPublicKey();
    }

    /**
     * Get backend theme
     * @return string
     */
    public function getTheme()
    {
        return $this->config->getBackendTheme();
    }

    /**
     * Get backend size
     * @return string
     */
    public function getSize()
    {
        return $this->config->getBackendSize();
    }

    /**
     * Return true if can display reCaptcha
     * @return bool
     */
    public function canDisplayCaptcha()
    {
        return $this->config->isEnabledBackend();
    }
}
