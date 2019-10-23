<?php

namespace Clerk\Clerk\Block;

use Clerk\Clerk\Model\Config;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart as CustomerCart;

class Powerstep extends AbstractProduct
{

    protected $cart;
    protected $priceHelper;


    public function __construct(
        CustomerCart $cart,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        $this->cart = $cart;
        $this->priceHelper = $priceHelper;
        parent::__construct($context);
    }

    public function getProductQtyCustom(){

        $counting = $this->cart->getSummaryQty();
        return $counting;
    }


    public function getSubtotalHtmlCustom()
    {
        $totals = $this->cart->getQuote()->getTotals();
        $subtotal = $totals['subtotal']['value'];
        $subtotal = $this->priceHelper->currency($subtotal, true, false);
        return $subtotal;
    }

    /**
     * Get Cart URL
     *
     * @return string
     */
    public function getCartUrl()
    {
        return $this->_cartHelper->getCartUrl();
    }

    /**
     * Get Checkout URL
     *
     * @return string
     */
    public function getCheckoutUrl()
    {
        return $this->getUrl('checkout', ['_secure' => true]);
    }

    /**
     * Get product added
     *
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->hasData('current_product')) {
            $this->setData('current_product', $this->_coreRegistry->registry('current_product'));
        }

        return $this->getData('current_product');
    }

    /**
     * Get image url for product
     *
     * @return string
     */
    public function getImageUrl()
    {
        $product = $this->getProduct();
        return $this->_imageHelper->init($product, 'product_page_image_small')
            ->setImageFile($product->getImage())
            ->getUrl();
    }

    public function getTemplates()
    {
        $configTemplates = $this->_scopeConfig->getValue(Config::XML_PATH_POWERSTEP_TEMPLATES);
        $templates = explode(',', $configTemplates);

        return (array) $templates;
    }
    
    public function generateRandomString($length = 25) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;

    }
}