define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/model/customer',
        'jquery',
        'Magento_Ui/js/form/form',
        'Magento_Customer/js/action/login',
        'Magento_Customer/js/model/customer',
        'mage/validation',
        'Magento_Checkout/js/model/authentication-messages',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (
        ko,
        Component,
        _,
        stepNavigator,
        customer,
        fullScreenLoader,
        messageContainer,
        loginAction
    ) {
        'use strict';
        var checkoutConfig = window.checkoutConfig;
        /**
        * check-login - is the name of the component's .html template
        */
        return Component.extend({
            isGuestCheckoutAllowed: checkoutConfig.isGuestCheckoutAllowed,
            isCustomerLoginRequired: checkoutConfig.isCustomerLoginRequired,
            registerUrl: checkoutConfig.registerUrl,
            forgotPasswordUrl: checkoutConfig.forgotPasswordUrl,
            autocomplete: checkoutConfig.autocomplete,
            defaults: {
                template: 'Vtearit_CheckoutLogin/checkout-login'
            },

            //add here your logic to display step,
            isVisible: ko.observable(true),
            isLogedIn: customer.isLoggedIn(),
            //step code will be used as step content id in the component template
            stepCode: 'checkLogin',
            //step title value
            stepTitle: 'Login Status',

            /**
            *
            * @returns {*}
            */
            initialize: function () {
                this._super();
                // register your step
                stepNavigator.registerStep(
                    this.stepCode,
                    //step alias
                    null,
                    this.stepTitle,
                    //observable property with logic when display step or hide step
                    this.isVisible,

                    _.bind(this.navigate, this),

                    /**
                    * sort order value
                    * 'sort order value' < 10: step displays before shipping step;
                    * 10 < 'sort order value' < 20 : step displays between shipping and payment step
                    * 'sort order value' > 20 : step displays after payment step
                    */
                    5
                );

                if(customer.isLoggedIn())
                {
                    window.location.href = checkoutConfig.checkoutUrl+"#shipping";
                }

                return this;
            },

            /**
            * The navigate() method is responsible for navigation between checkout step
            * during checkout. You can add custom logic, for example some conditions
            * for switching to your custom step
            */
            navigate: function () {
                this.isVisible(true);
            },

            isActive: function () {
                return !customer.isLoggedIn();
                
            },

            /**
            * @returns void
            */
             navigateToNextStep: function () {
                stepNavigator.next();
            },

            /**
             * Provide login action.
             *
             * @param {HTMLElement} loginForm
             */
             login: function (loginForm) {
                var loginData = {},
                    formDataArray = jQuery(loginForm).serializeArray();

                formDataArray.forEach(function (entry) {
                    loginData[entry.name] = entry.value;
                });

                if (jQuery(loginForm).validation() &&
                jQuery(loginForm).validation('isValid')
                ) {
                    // fullScreenLoader.startLoader();
                    jQuery('body').trigger('processStart');
                    loginAction(loginData, checkoutConfig.checkoutUrl, undefined).always(function () {

                    jQuery('body').trigger('processStop');
                        // fullScreenLoader.stopLoader();
                    });
                }
            }
        });
    }
);