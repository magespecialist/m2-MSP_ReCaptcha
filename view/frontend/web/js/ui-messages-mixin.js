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

define(['MSP_ReCaptcha/js/registry'], function (registry) {
  'use strict';

  return function (originalComponent) {
    'use strict';

    return originalComponent.extend({
      initialize: function () {
        this._super();

        this.messageContainer.errorMessages.subscribe(function (newValue) {
          registry.captchaList().forEach(function (captcha) {
            grecaptcha.reset(captcha);
          });
        }, null, "arrayChange");

        return this;
      }
    });
  };
});