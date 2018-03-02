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

namespace MSP\ReCaptcha\Model\Provider\Failure;

use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\UrlInterface;
use MSP\ReCaptcha\Model\Config;
use MSP\ReCaptcha\Model\Provider\FailureProviderInterface;

class ObserverRedirectFailure implements FailureProviderInterface
{
    /**
     * @var MessageManagerInterface
     */
    private $messageManager;

    /**
     * @var ActionFlag
     */
    private $actionFlag;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var RedirectUrlProviderInterface
     */
    private $redirectUrlProvider;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * RedirectFailure constructor.
     * @param MessageManagerInterface $messageManager
     * @param ActionFlag $actionFlag
     * @param Config $config
     * @param UrlInterface $url
     * @param RedirectUrlProviderInterface|null $redirectUrlProvider
     */
    public function __construct(
        MessageManagerInterface $messageManager,
        ActionFlag $actionFlag,
        Config $config,
        UrlInterface $url,
        RedirectUrlProviderInterface $redirectUrlProvider = null
    ) {
        $this->messageManager = $messageManager;
        $this->actionFlag = $actionFlag;
        $this->config = $config;
        $this->redirectUrlProvider = $redirectUrlProvider;
        $this->url = $url;
    }

    /**
     * Get redirect URL
     * @return string
     */
    private function getUrl()
    {
        return $this->redirectUrlProvider->execute();
    }

    /**
     * Handle reCaptcha failure
     * @param ResponseInterface $response
     * @return void
     */
    public function execute(ResponseInterface $response = null)
    {
        $this->messageManager->addErrorMessage($this->config->getErrorDescription());
        $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);

        $response->setRedirect($this->getUrl());
    }
}
