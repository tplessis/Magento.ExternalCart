<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/AbstractOB.php');

/**
 * Cart template class 
 *
 */
class OBCatalogProduct extends AbstractOB {
    
    /**
     * Product ID
     * @var int
     */
    protected $id;
    
    /**
     * Product SKU
     * @var double
     */
    protected $sku;
    
    /**
     * Product name
     * @var string
     */
    protected $name;
    
    /**
     * Product description
     * @var string
     */
    protected $description;
    
    /**
     * Product price
     * @var double
     */
    protected $price;
    
     /**
     * Constructor
     * 
     */
    public function __construct($sku, $storeView) {
        $this->sku = $sku;
        $this->storeView = $storeView;
        $this->soapConnect();
    }
    
    /**
     * Load product data
     * @return boolean
     */
    public function load() {
        try {
            $result = $this->soapClient->call($this->soapSession, 'catalog_product.info', $this->sku.' ', $this->storeView);
            $this->price = $result['price'];
            $this->id = $result['product_id'];
            $this->name = $result['name'];
            $this->description = $result['description'];
            return TRUE;
        } catch (SoapFault $error) {
            return FALSE;
        }
    }
    
    /**
     * Returns product id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Set product id
     *
     * @param int $id
     * @return void
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns item price
     *
     * @return double
     */
    public function getPrice() {
        return $this->price;
    }
    
    /**
     * Set item price
     *
     * @param double $price
     * @return void
     */
    public function setPrice($price) {
        $this->price = $price;
    }
    
    /**
     * Returns item sku
     *
     * @return string
     */
    public function getSku() {
        return $this->sku;
    }
    
    /**
     * Set item sku
     *
     * @param string $sku
     * @return void
     */
    public function setSku($sku) {
        $this->sku = $sku;
    }
    
    /**
     * Returns item name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Set item name
     *
     * @param string $name
     * @return void
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Returns item description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }
    
    /**
     * Set item description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description) {
        $this->description = $description;
    }
    
}
?>