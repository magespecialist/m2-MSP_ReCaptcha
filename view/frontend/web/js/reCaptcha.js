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
    'jquery',
    'ko',
    'MSP_ReCaptcha/js/registry',
    'https://www.google.com/recaptcha/api.js'
  ],
  function (Component, $, ko, registry, reCaptcha) {
    'use strict';

    return Component.extend({
      defaults: {
        template: 'MSP_ReCaptcha/reCaptcha'
      },
      getIsVisible: function () {
        return window.mspReCaptchaConfig.enabled[this.zone];
      },
      reCaptchaCallback: function (token) {
        if (window.mspReCaptchaConfig.size === 'invisible') {
          this.tokenField.value = token;
          this.$parentForm.submit();
        }
      },
      initCaptcha: function () {
        if (this.captchaInitialized) {
          return;
        }

        this.captchaInitialized = true;

        var $parentForm = $('#' + this.getReCaptchaId()).parents('form');
        var me = this;

        var widgetId = grecaptcha.render(this.getReCaptchaId(), {
          'sitekey': window.mspReCaptchaConfig.siteKey,
          'theme': window.mspReCaptchaConfig.theme,
          'size': window.mspReCaptchaConfig.size,
          'badge': this.badge ? this.badge : window.mspReCaptchaConfig.badge,
          'callback': function (token) { me.reCaptchaCallback(token); }
        });

        if (window.mspReCaptchaConfig.size === 'invisible') {
          $parentForm.submit(function (event) {
            if (!me.tokenField.value) {
              grecaptcha.execute(widgetId);
              event.preventDefault(event);
              event.stopImmediatePropagation();
            }
          });

          // Move our (last) handler topmost. We need this to avoid submit bindings with ko.
          var listeners = $._data($parentForm[0], 'events').submit;
          listeners.unshift(listeners.pop());

          // Create a virtual token field
          this.tokenField = $('<input type="text" name="token" style="display: none" />')[0];
          this.$parentForm = $parentForm;
          $parentForm.append(this.tokenField);
        } else {
          this.tokenField = null;
        }

        registry.ids.push(this.getReCaptchaId());
        registry.captchaList.push(widgetId);
        registry.tokenFields.push(this.tokenField);

      },
      renderReCaptcha: function () {
        if (this.getIsVisible()) {
          this.initCaptcha();
        }
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
