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

namespace MSP\ReCaptcha\Model\Provider\Failure\RedirectUrl;

use Magento\Customer\Model\Url;
use Magento\Framework\Session\SessionManagerInterface;
use MSP\ReCaptcha\Model\Provider\Failure\RedirectUrlProviderInterface;

class BeforeAuthUrlProvider implements RedirectUrlProviderInterface
{
    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var Url
     */
    private $url;

    /**
     * BeforeAuthUrlProvider constructor.
     * @param SessionManagerInterface $sessionManager
     * @param Url $url
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        Url $url
    ) {
        $this->sessionManager = $sessionManager;
        $this->url = $url;
    }

    /**
     * Get redirection URL
     * @return string
     */
    public function execute()
    {
        $beforeUrl = $this->sessionManager->getBeforeAuthUrl();
        return $beforeUrl ?: $this->url->getLoginUrl();
    }
}
