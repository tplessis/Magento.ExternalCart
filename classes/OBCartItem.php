<?php
require_once(__DIR__ . '/AbstractOB.php');
require_once(__DIR__ . '/OBTemplate.php');
require_once(__DIR__ . '/OBCatalogProduct.php');

/**
 * A cart item
 */
class OBCartItem extends AbstractOB {
    
    /**
     * Product
     * @var OBCatalogProduct
     */
    protected $product;
    
    /**
     * Product quantity
     * @var int
     */
    protected $quantity;    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->view = new OBTemplate(TEMPLATE_PATH_ITEM);
    }
    
    /**
     * Returns product
     *
     * @return OBCatalogProduct
     */
    public function getProduct() {
        return $this->product;
    }
    
    /**
     * Set product
     *
     * @param $product OBCatalogProduct
     */
    public function setProduct($product) {
        $this->product = $product;
    }
    
    /**
     * Returns item quantity
     *
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }
    
    /**
     * Set item quantity
     *
     * @param int $quantity
     * @return void
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
    
    /**
     * Returns item total price
     *
     * @return double
     */
    public function getTotalPrice() {
        return $this->product->getPrice() * $this->quantity;
    }
    
    /**
     * Parse item template
     *
     * @return void
     */
    public function parse() {
        $this->view->assign('##ITEM_NAME##', $this->product->getName());
        $this->view->assign('##ITEM_PRICE##', number_format($this->product->getPrice(), 2, ',', ' '));
        $this->view->assign('##ITEM_TOTAL_PRICE##', number_format($this->getTotalPrice(), 2, ',', ' '));
        $this->view->assign('##ITEM_SKU##', $this->product->getSku());
        $this->view->assign('##ITEM_ID##', $this->product->getId());
        $this->view->assign('##ITEM_QUANTITY##', $this->quantity);
    }
    
}
?>