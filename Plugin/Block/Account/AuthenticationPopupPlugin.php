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

namespace MSP\ReCaptcha\Plugin\Block\Account;

use Magento\Framework\Json\DecoderInterface;
use Magento\Framework\Json\EncoderInterface;
use MSP\ReCaptcha\Model\LayoutSettings;

class AuthenticationPopupPlugin
{
    /**
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var LayoutSettings
     */
    private $layoutSettings;

    /**
     * AuthenticationPopupPlugin constructor.
     * @param EncoderInterface $encoder
     * @param DecoderInterface $decoder
     * @param LayoutSettings $layoutSettings
     */
    public function __construct(
        EncoderInterface $encoder,
        DecoderInterface $decoder,
        LayoutSettings $layoutSettings
    ) {
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->layoutSettings = $layoutSettings;
    }

    /**
     * @param \Magento\Customer\Block\Account\AuthenticationPopup $subject
     * @param array $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetJsLayout(\Magento\Customer\Block\Account\AuthenticationPopup $subject, $result)
    {
        $layout = $this->decoder->decode($result);
        $layout['components']['authenticationPopup']['children']['msp_recaptcha']['settings'] =
            $this->layoutSettings->getCaptchaSettings();

        return $this->encoder->encode($layout);
    }
}
