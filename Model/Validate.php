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

namespace MSP\ReCaptcha\Model;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Magento\Framework\Json\DecoderInterface;
use MSP\ReCaptcha\Api\ValidateInterface;
use MSP\ReCaptcha\Helper\Data;
use ReCaptcha\ReCaptcha;
use Magento\Framework\App\RequestInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class Validate implements ValidateInterface
{
    protected $requestInterface;
    protected $remoteAddress;
    protected $helperData;
    protected $jsonDecoderInterface;

    public function __construct(
        RequestInterface $requestInterface,
        RemoteAddress $remoteAddress,
        Data $helperData,
        DecoderInterface $jsonDecoderInterface
    ) {
        $this->requestInterface = $requestInterface;
        $this->remoteAddress = $remoteAddress;
        $this->helperData = $helperData;
        $this->jsonDecoderInterface = $jsonDecoderInterface;
    }

    /**
     * Return true if reCaptcha validation has passed
     * @return bool
     */
    public function validate()
    {
        $secret = $this->helperData->getPrivateKey();

        $userIp = $this->remoteAddress->getRemoteAddress();

        $reCatchaResponse = $this->requestInterface->getParam('g-recaptcha-response', '');

        // Check if it is a JSON payload
        if (!$reCatchaResponse) {
            $content = $this->requestInterface->getContent();
            if ($content) {
                try {
                    $jsonParams = $this->jsonDecoderInterface->decode($content);
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
