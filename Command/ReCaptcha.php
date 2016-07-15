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

namespace MSP\ReCaptcha\Command;

use Magento\Framework\App\Cache\Manager;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReCaptcha extends Command
{
    protected $configInterface;
    protected $cacheManager;

    public function __construct(
        ConfigInterface $configInterface,
        Manager $cacheManager
    ) {
        $this->configInterface = $configInterface;
        $this->cacheManager = $cacheManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('msp:security:recaptcha:disable');
        $this->setDescription('Disable backend reCaptcha');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configInterface->saveConfig(
            'msp_recaptcha/general/enabled_backend',
            '0',
            'default',
            0
        );

        $this->cacheManager->flush(['config']);
    }
}
