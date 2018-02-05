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

namespace MSP\ReCaptcha\Observer;

use Magento\Framework\App\Area;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Json\DecoderInterface;
use MSP\ReCaptcha\Api\ValidateInterface;
use MSP\ReCaptcha\Model\Config;
use MSP\ReCaptcha\Model\Provider\FailureProviderInterface;
use MSP\ReCaptcha\Model\Provider\ResponseProviderInterface;

class ReCaptchaObserver implements ObserverInterface
{
    /**
     * @var FailureProviderInterface
     */
    private $failureProvider;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string $area
     */
    private $area;

    /**
     * @var string
     */
    private $enableConfigFlag;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ValidateInterface
     */
    private $validate;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var ResponseProviderInterface
     */
    private $responseProvider;

    /**
     * @var string
     */
    private $requireRequestParam;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * LoginObserver constructor.
     * @param ResponseProviderInterface $responseProvider
     * @param RequestInterface $request
     * @param ValidateInterface $validate
     * @param FailureProviderInterface $failureProvider
     * @param Config $config
     * @param ScopeConfigInterface $scopeConfig
     * @param RemoteAddress $remoteAddress
     * @param DecoderInterface $decoder
     * @param string $area
     * @param string $enableConfigFlag
     * @param bool $requireRequestParam
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ResponseProviderInterface $responseProvider,
        RequestInterface $request,
        ValidateInterface $validate,
        FailureProviderInterface $failureProvider,
        Config $config,
        ScopeConfigInterface $scopeConfig,
        RemoteAddress $remoteAddress,
        DecoderInterface $decoder,
        $area,
        $enableConfigFlag = '',
        $requireRequestParam = false
    ) {
        $this->responseProvider = $responseProvider;
        $this->request = $request;
        $this->validate = $validate;
        $this->failureProvider = $failureProvider;
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
        $this->remoteAddress = $remoteAddress;
        $this->decoder = $decoder;
        $this->area = $area;
        $this->enableConfigFlag = $enableConfigFlag;
        $this->requireRequestParam = $requireRequestParam;
    }

    /**
     * Return true if check should be skipped
     * @return bool
     */
    private function isCheckNotRequired()
    {
        return (
            (($this->area === Area::AREA_ADMINHTML) && !$this->config->isEnabledBackend()) ||
            (($this->area === Area::AREA_FRONTEND) && !$this->config->isEnabledFrontend()) ||
            ($this->enableConfigFlag && !$this->scopeConfig->getValue($this->enableConfigFlag)) ||
            ($this->requireRequestParam && !$this->request->getParam($this->requireRequestParam))
        );
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->isCheckNotRequired()) {
            return;
        }

        $reCaptchaResponse = $this->responseProvider->execute();
        $remoteIp = $this->remoteAddress->getRemoteAddress();

        if (!$this->validate->validate($reCaptchaResponse, $remoteIp)) {
            $this->failureProvider->execute($observer);
        }
    }
}
