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

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;

class IsCheckRequired implements IsCheckRequiredInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $enableConfigFlag;

    /**
     * @var bool
     */
    private $requireRequestParam;

    /**
     * @var string
     */
    private $area;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * IsCheckRequired constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param RequestInterface $request
     * @param Config $config
     * @param string $area
     * @param string $enableConfigFlag
     * @param bool $requireRequestParam
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        RequestInterface $request,
        Config $config,
        $area = null,
        $enableConfigFlag = null,
        $requireRequestParam = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->config = $config;
        $this->enableConfigFlag = $enableConfigFlag;
        $this->requireRequestParam = $requireRequestParam;
        $this->area = $area;
        $this->request = $request;

        if (!in_array($this->area, [Area::AREA_FRONTEND, Area::AREA_ADMINHTML])) {
            throw new \InvalidArgumentException('Area parameter must be one of frontend or adminhtml');
        }
    }

    /**
     * Return true if area is configured to be active
     * @return bool
     */
    private function isAreaEnabled()
    {
        return
            (($this->area === Area::AREA_ADMINHTML) && $this->config->isEnabledBackend()) ||
            (($this->area === Area::AREA_FRONTEND) && $this->config->isEnabledFrontend());
    }

    /**
     * Return true if current zone is enabled
     * @return bool
     */
    private function isZoneEnabled()
    {
        return !$this->enableConfigFlag || $this->scopeConfig->getValue($this->enableConfigFlag);
    }

    /**
     * Return true if request if valid
     * @return bool
     */
    private function isRequestValid()
    {
        return !$this->requireRequestParam || $this->request->getParam($this->requireRequestParam);
    }

    /**
     * Return true if check is required
     * @return bool
     */
    public function execute()
    {
        return
            $this->isAreaEnabled() &&
            $this->isZoneEnabled() &&
            $this->isRequestValid();
    }
}
