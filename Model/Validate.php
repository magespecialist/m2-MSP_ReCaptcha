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

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Json\DecoderInterface;
use MSP\ReCaptcha\Api\ValidateInterface;
use MSP\SecuritySuiteCommon\Api\LogManagementInterface;
use Magento\Framework\Event\ManagerInterface as EventInterface;
use ReCaptcha\ReCaptcha;
use Magento\Framework\App\RequestInterface;

class Validate implements ValidateInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RemoteAddress
     */
    private $remoteAddress;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var DecoderInterface
     */
    private $jsonDecoder;

    /**
     * @var EventInterface
     */
    private $event;

    public function __construct(
        RequestInterface $request,
        RemoteAddress $remoteAddress,
        Config $config,
        DecoderInterface $jsonDecoder,
        EventInterface $event
    ) {
        $this->request = $request;
        $this->remoteAddress = $remoteAddress;
        $this->config = $config;
        $this->jsonDecoder = $jsonDecoder;
        $this->event = $event;
    }

    /**
     * Return true if reCaptcha validation has passed
     * @return bool
     */
    public function validate()
    {
        if (!$this->_validate()) {
            $this->event->dispatch(LogManagementInterface::EVENT_ACTIVITY, [
                'module' => 'MSP_ReCaptcha',
                'message' => 'Invalid reCaptcha',
            ]);

            return false;
        }

        return true;
    }

    protected function _validate()
    {
        $secret = $this->config->getPrivateKey();

        $userIp = $this->remoteAddress->getRemoteAddress();

        $reCatchaResponse = $this->request->getParam('g-recaptcha-response', '');

        // Check if it is a JSON payload
        if (!$reCatchaResponse) {
            $content = $this->request->getContent();
            if ($content) {
                try {
                    $jsonParams = $this->jsonDecoder->decode($content);
                    if (isset($jsonParams['g-recaptcha-response'])) {
                        $reCatchaResponse = $jsonParams['g-recaptcha-response'];
                    }
                } catch (\Exception $e) {
                    $reCatchaResponse = '';
                }
            }
        }

        if (!$reCatchaResponse) {
            return false;
        }

        $reCaptcha = new ReCaptcha($secret);
        $res = $reCaptcha->verify($reCatchaResponse, $userIp);

        return $res->isSuccess();
    }
}
