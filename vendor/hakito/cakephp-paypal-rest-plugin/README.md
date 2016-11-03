[![Latest Stable Version](https://poser.pugx.org/hakito/cakephp-paypal-rest-plugin/v/stable.svg)](https://packagist.org/packages/hakito/cakephp-paypal-rest-plugin) [![Total Downloads](https://poser.pugx.org/hakito/cakephp-paypal-rest-plugin/downloads.svg)](https://packagist.org/packages/hakito/cakephp-paypal-rest-plugin) [![Latest Unstable Version](https://poser.pugx.org/hakito/cakephp-paypal-rest-plugin/v/unstable.svg)](https://packagist.org/packages/hakito/cakephp-paypal-rest-plugin) [![License](https://poser.pugx.org/hakito/cakephp-paypal-rest-plugin/license.svg)](https://packagist.org/packages/hakito/cakephp-paypal-rest-plugin)

CakePHP-PayPalRest-Plugin
=========================

Simple PayPal plugin for CakePHP using the REST api.

Installation
------------

If you are using composer simply add the following requirement to your composer.json:

```json
{
  "require": { "hakito/cakephp-paypal-rest-plugin": ">=1.0" }
}
```

Without composer download the plugin to your app/Plugin directory. This plugin requires the PayPal REST api.

Model
-----
Add the following table to your database.

```sql
CREATE TABLE IF NOT EXISTS `PayPalPayments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `payment_id` varchar(50) DEFAULT NULL,
  `payment_state` enum('created','approved','failed','canceled','expired','pending') DEFAULT NULL,
  `sale_state` enum('pending','completed','refunded','partially_refunded') DEFAULT NULL,
  `remittance_identifier` varchar(100) NOT NULL,
  `remitted_moment` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_id` (`payment_id`),
  KEY `sale_state` (`sale_state`,`remitted_moment`),
  KEY `payment_state` (`payment_state`),
  KEY `payment_state_2` (`payment_state`,`sale_state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
```

Configuration
-------------

You can find a sample configuration in Config/bootstrap.php. Just override the settings in your own bootstrap.php.

Usage
-----

Following is the minimal set to start a payment request:

```php
class OrdersController extends AppController {
    public $components = array('PayPal.PayPal');
    
    public yourPaymentAction($order) {
        foreach ($order['OrderedItem'] as $orderedItem)
        {
            $quantity = $orderedItem['quantity'];
            $price = $orderedItem['price'];
            $itemName = $orderedItem['name'];
            $itemId = $orderedItem['id'];
            
            // money values are always integer values in cents
            $this->PayPal->AddArticle($itemName, $quantity, $price, $itemId); 
        }

        // optional shipping fee
        $this->PayPal->Shipping = 123; // money values are always integer values in cents
        
        // Url the client is redirected to when PayPal payment is performed successfully
        // NOTE: This does not mean that the payment is COMPLETE.
        $okUrl =  Router::url('/paymentOk', true);
        
        // Url the client is redirected to whe PayPal payment fails or was cancelled
        $nOkUrl = Router::url('/paymentFailed', true);
        
        return $this->PayPal->PaymentRedirect($order['id'], $okUrl, $nOkUrl);    
    }
}
```

To receive the payment notifications in your app the Plugin needs 3 functions available in your AppModel.php
```php

    public function beforePayPalPaymentExecution($orderId)
    {
        // Will be called just after PayPal redirects the customer
        // back to your site. (You could begin a transaction here)
        // True is always expected as return value, otherwise the plugin
        // will throw an exception
        return true; 
    }

    public function cancelPayPalPaymentExecution($orderId)
    {
        // Will be called when the REST api call fails or
        // the saleState != 'completed' or paymentState != 'approved'
        // (You could rollback a transaction here)
    }

    public function afterPayPalPaymentExecution($orderId)
    {
        // Will be called after the REST api call
        // and only if the saleState == 'completed' and paymentState == 'approved'
        // (You could commit a transaction here)
    }

```

Remarks
-------

The current implementation does not support automatic handling payments in pending state. 

Donate
------

Any donation is welcome

* PayPal: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RLE88DG8CSVUE
* Bitcoin: 1QHLTMZDwTJqUK9VZWa1RKtPCHnT7wTu3q
