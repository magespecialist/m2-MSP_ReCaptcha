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

use MSP\ReCaptcha\Api\ValidateInterface;
use ReCaptcha\ReCaptcha;

class Validate implements ValidateInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Validate constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Return true if reCaptcha validation has passed
     * @param string $reCaptchaResponse
     * @param string $remoteIp
     * @return bool
     */
    public function validate($reCaptchaResponse, $remoteIp)
    {
        $secret = $this->config->getPrivateKey();

        if ($reCaptchaResponse) {
            // @codingStandardsIgnoreStart
            $reCaptcha = new ReCaptcha($secret);
            // @codingStandardsIgnoreEmd

            $res = $reCaptcha->verify($reCaptchaResponse, $remoteIp);

            if ($res->isSuccess()) {
                return true;
            }
        }

        return false;
    }
}
