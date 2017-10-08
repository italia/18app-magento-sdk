<?php
require_once 'Mage/Checkout/controllers/CartController.php';

class HLCS_Checkout_CartController extends Mage_Checkout_CartController {

public function couponPostAction()
  {
         /**
          * No reason continue with empty shopping cart
          */
         if (!$this->_getCart()->getQuote()->getItemsCount()) {
             $this->_goBack();
             return;
         }
 
         $couponCode = (string) $this->getRequest()->getParam('coupon_code');
         if ($this->getRequest()->getParam('remove') == 1) {
             $couponCode = '';
         }
         $oldCouponCode = $this->_getQuote()->getCouponCode();
 
         if (!strlen($couponCode) && !strlen($oldCouponCode)) {
           $this->_goBack();
           return;
      }
 
        try {
            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
                ->collectTotals()
                 ->save();

             if ($couponCode) {
                 if ($couponCode == $this->_getQuote()->getCouponCode()) {
                    $this->_getSession()->addSuccess(
                         $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->htmlEscape($couponCode))
                );
               }
                 else {
                    $this->_getSession()->addError(
                        $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode))
						);}
					 Mage::log("{$couponCode} is not a Magento Coupon", null, '18App-Coupon.log');
					
                 }
           } else {
                 $this->_getSession()->addSuccess($this->__('Coupon code was canceled successfully.'));
            }
 
         }
         catch (Mage_Core_Exception $e) {
             $this->_getSession()->addError($e->getMessage());
         }
         catch (Exception $e) {
             $this->_getSession()->addError($this->__('Can not apply coupon code.'));
         }
 
         $this->_goBack();
     }
	 
		
	 
	 
}