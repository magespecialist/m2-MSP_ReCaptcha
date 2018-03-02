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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use MSP\ReCaptcha\Api\ValidateInterface;
use MSP\ReCaptcha\Model\IsCheckRequiredInterface;
use MSP\ReCaptcha\Model\Provider\FailureProviderInterface;
use MSP\ReCaptcha\Model\Provider\ResponseProviderInterface;

class ReCaptchaObserver implements ObserverInterface
{
    /**
     * @var FailureProviderInterface
     */
    private $failureProvider;

    /**
     * @var ValidateInterface
     */
    private $validate;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var ResponseProviderInterface
     */
    private $responseProvider;

    /**
     * @var IsCheckRequiredInterface
     */
    private $isCheckRequired;

    /**
     * ReCaptchaObserver constructor.
     * @param ResponseProviderInterface $responseProvider
     * @param ValidateInterface $validate
     * @param FailureProviderInterface $failureProvider
     * @param RemoteAddress $remoteAddress
     * @param IsCheckRequiredInterface $isCheckRequired
     */
    public function __construct(
        ResponseProviderInterface $responseProvider,
        ValidateInterface $validate,
        FailureProviderInterface $failureProvider,
        RemoteAddress $remoteAddress,
        IsCheckRequiredInterface $isCheckRequired
    ) {
        $this->responseProvider = $responseProvider;
        $this->validate = $validate;
        $this->failureProvider = $failureProvider;
        $this->remoteAddress = $remoteAddress;
        $this->isCheckRequired = $isCheckRequired;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->isCheckRequired->execute()) {
            $reCaptchaResponse = $this->responseProvider->execute();
            $remoteIp = $this->remoteAddress->getRemoteAddress();

            /** @var \Magento\Framework\App\Action\Action $controller */
            $controller = $observer->getControllerAction();

            if (!$this->validate->validate($reCaptchaResponse, $remoteIp)) {
                $this->failureProvider->execute($controller ? $controller->getResponse(): null);
            }
        }
    }
}
