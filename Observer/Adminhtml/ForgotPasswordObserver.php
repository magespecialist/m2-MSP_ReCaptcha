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

namespace MSP\ReCaptcha\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MSP\ReCaptcha\Api\ValidateInterface;
use MSP\ReCaptcha\Helper\Data;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Action\Action;

class ForgotPasswordObserver implements ObserverInterface
{
    protected $validateInterface;
    protected $helperData;
    protected $messageManager;
    protected $actionFlag;

    public function __construct(
        ValidateInterface $validateInterface,
        Data $helperData,
        ManagerInterface $messageManager,
        ActionFlag $actionFlag
    ) {
        $this->validateInterface = $validateInterface;
        $this->helperData = $helperData;
        $this->messageManager = $messageManager;
        $this->actionFlag = $actionFlag;
    }

    public function execute(Observer $observer)
    {
        if (!$this->helperData->getEnabledBackend()) {
            return;
        }

        $controller = $observer->getControllerAction();
        $email = (string)$observer->getControllerAction()->getRequest()->getParam('email');

        if (!$this->validateInterface->validate() && $email) {
            $this->messageManager->addError(__('Incorrect CAPTCHA'));
            $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
            $controller->getResponse()->setRedirect(
                $controller->getUrl('*/*/forgotpassword')
            );
        }
    }
}
