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

/*browser:true jquery:true*/
/*global define*/
define(
  [
    'uiComponent',
    'MSP_ReCaptcha/js/registry',
    'https://www.google.com/recaptcha/api.js'
  ],
  function (Component, registry, reCaptcha) {
    'use strict';

    return Component.extend({
      defaults: {
        template: 'MSP_ReCaptcha/reCaptcha'
      },
      getIsVisible: function () {
        return window.mspReCaptchaConfig.enabled;
      },
      getSiteKey: function () {
        return window.mspReCaptchaConfig.siteKey;
      },
      renderReCaptcha: function () {
        registry.ids.push(this.getReCaptchaId());

        registry.captchaList.push(grecaptcha.render(this.getReCaptchaId(), {
          'sitekey': this.getSiteKey()
        }));
      },
      getReCaptchaId: function () {
        if (!this.reCaptchaId) {
          return 'msp-recaptcha';
        }

        return this.reCaptchaId;
      }
    });
  }
);
