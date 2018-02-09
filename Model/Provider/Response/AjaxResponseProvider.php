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

namespace MSP\ReCaptcha\Model\Provider\Response;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Json\DecoderInterface;
use MSP\ReCaptcha\Model\Provider\ResponseProviderInterface;

class AjaxResponseProvider implements ResponseProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * AjaxResponseProvider constructor.
     * @param RequestInterface $request
     * @param DecoderInterface $decoder
     */
    public function __construct(
        RequestInterface $request,
        DecoderInterface $decoder
    ) {
        $this->request = $request;
        $this->decoder = $decoder;
    }

    /**
     * Handle reCaptcha failure
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute()
    {
        if ($content = $this->request->getContent()) {
            try {
                $jsonParams = $this->decoder->decode($content);
                if (isset($jsonParams['g-recaptcha-response'])) {
                    return $jsonParams['g-recaptcha-response'];
                }
            } catch (\Exception $e) {
                return '';
            }
        }
    }
}
