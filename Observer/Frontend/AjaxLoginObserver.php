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

namespace MSP\ReCaptcha\Observer\Frontend;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\Json\EncoderInterface;
use MSP\ReCaptcha\Api\ValidateInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Action\Action;
use MSP\ReCaptcha\Model\Config;

class AjaxLoginObserver implements ObserverInterface
{
    /**
     * @var ValidateInterface
     */
    private $validate;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ActionFlag
     */
    private $actionFlag;

    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    public function __construct(
        ValidateInterface $validate,
        Config $config,
        ActionFlag $actionFlag,
        EncoderInterface $encoder,
        DecoderInterface $decoder,
        RemoteAddress $remoteAddress
    ) {

        $this->validate = $validate;
        $this->config = $config;
        $this->actionFlag = $actionFlag;
        $this->encoder = $encoder;
        $this->remoteAddress = $remoteAddress;
        $this->decoder = $decoder;
    }

    /**
     * Extract reCaptcha response from JSON body payload
     * @param RequestInterface $request
     * @return string
     */
    private function getReCaptchaResponse(RequestInterface $request)
    {
        if ($content = $request->getContent()) {
            try {
                $jsonParams = $this->decoder->decode($content);
                if (isset($jsonParams['g-recaptcha-response'])) {
                    return $jsonParams['g-recaptcha-response'];
                }
            } catch (\Exception $e) {
                return '';
            }
        }

        return '';
    }

    public function execute(Observer $observer)
    {
        if (!$this->config->isEnabledFrontendLogin()) {
            return;
        }

        /** @var \Magento\Framework\App\Action\Action $controller */
        $controller = $observer->getControllerAction();
        $request = $controller->getRequest();

        $reCaptchaResponse = $this->getReCaptchaResponse($request);
        $remoteIp = $this->remoteAddress->getRemoteAddress();

        if (!$this->validate->validate($reCaptchaResponse, $remoteIp)) {
            $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
            
            $jsonPayload = $this->encoder->encode([
                'errors' => true,
                'message' => $this->config->getErrorDescription(),
            ]);
            $controller->getResponse()->representJson($jsonPayload);
        }
    }
}
