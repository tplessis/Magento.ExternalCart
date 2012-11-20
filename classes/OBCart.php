<?php
require_once(__DIR__ . '/AbstractOB.php');
require_once(__DIR__ . '/OBTemplate.php');

/**
 * Manage OB Magento cart
 */
class OBCart extends AbstractOB {
    
    /**
     * The OB shopping cart ID
     * @var int
     */
    protected $cartId = NULL;
    
    /**
     * Array of OBItems
     * @var array
     */
    protected $items = array();
    
    /**
     * Total price of the cart
     * @var double
     */
    protected $totalPrice;
    
    
    /**
     * Constructor
     * @param string $storeView Name of the magento store view
     */
    public function __construct($storeView) {
        parent::__construct();
        $this->storeView = $storeView;
        $this->cartId = $_SESSION['OB_cartID'];
        $this->totalPrice = 0.0;
        
        // Init view
        $this->view = new OBTemplate(TEMPLATE_PATH_CART);
        
        // Connect SOAP client
        $this->soapConnect();
        
        // Create cart if needed
        if(!$this->cartId) {
            $this->cartId = $this->soapClient->call($this->soapSession, 'cart.create', array($this->storeView));
            $_SESSION['OB_cartID'] = $this->cartId;
        }
        
        // Set customer address as guest
        $this->setCustomerAddress(NULL);
    }
    
    /**
     * Login SOAP client
     *
     * @return boolean
     */
    protected function soapConnect() {
        // Connect SOAP client
        $this->soapClient = new SoapClient(OB_ROOT_URL.'/boutique/api/soap/?wsdl', array('exceptions' => 1));
        $this->soapSession = $this->soapClient->login(SOAP_LOGIN, SOAP_PASSWORD);
    }
    
    /**
     * Load cart items
     *
     * @return void
     */
    public function loadItems() {
        $this->totalPrice = 0;    
        $cartInfos = $this->soapClient->call($this->soapSession, 'cart.info', $this->cartId, $this->storeView);

        if(is_array($cartInfos['items'])) {
            foreach ($cartInfos['items'] as $product) {
                $item = new OBCartItem();
                $catalogProduct = new OBCatalogProduct($product['sku'], $this->storeView);
                $catalogProduct->setId($product['product_id']);
                $catalogProduct->setName($product['name']);
                $catalogProduct->setDescription($product['description']);
                $catalogProduct->setPrice($product['base_price']);
                $item->setProduct($catalogProduct);
                $item->setQuantity($product['qty']);
                array_push($this->items, $item);
                
                // Increase total price of the cart
                $this->totalPrice += $item->getQuantity() * $item->getProduct()->getPrice();
            }
        }
    }
    
    /**
     * Add item to cart
     *
     * @param string $sku SKU of product to add
     * @param int $quantity Quantity of product to add
     * @return boolean
     */
    public function addItem($sku, $quantity) {
        $result = $this->soapClient->call(
            $this->soapSession,
            'cart_product.add',
            array(
                $this->cartId,
                array (
                    array(
                        'sku' => $sku,
                        'quantity' => $quantity
                    )
                ),
                $this->storeView
            )
        );
        return $result;
    }
    
    /**
     * Update item's quantity in the cart
     *
     * @param int $productId ID of product to update
     * @param int $quantity Quantity of product to update
     * @return boolean
     */
    public function updateItem($productId, $quantity) {
        $result = $this->soapClient->call(
            $this->soapSession,
            'cart_product.update',
            array(
                $this->cartId,
                array (
                    array(
                        'product_id' => $productId,
                        'qty' => $quantity
                    )
                ),
                $this->storeView
            )
        );
        return $result;
    }
    
    /**
     * Update item's quantity in the cart
     *
     * @param string $sku SKU of product to add
     * @return boolean
     */
    public function removeItem($sku) {
        $result = $this->soapClient->call(
            $this->soapSession,
            'cart_product.remove',
            array(
                $this->cartId,
                array (
                    array(
                        'sku' => $sku
                    )
                ),
                $this->storeView
            )
        );
        return $result;
    }
    
    /**
     * Add customer addresses (shipping and billing)
     *
     * @param array $address Array of customer addresses
     * @return boolean
     */
    public function setCustomerAddress($address) {
        // Address for guest user
        if($addresses == NULL) {
            $address = array(
                array(
                    "mode" => "shipping",
                    "firstname" => "testFirstname",
                    "lastname" => "testLastname",
                    "company" => "testCompany",
                    "street" => "testStreet",
                    "city" => "testCity",
                    "region" => "testRegion",
                    "postcode" => "testPostcode",
                    "country_id" => "id",
                    "telephone" => "0123456789",
                    "fax" => "0123456789",
                    "is_default_shipping" => 0,
                    "is_default_billing" => 0
                ),
                array(
                    "mode" => "billing",
                    "firstname" => "testFirstname",
                    "lastname" => "testLastname",
                    "company" => "testCompany",
                    "street" => "testStreet",
                    "city" => "testCity",
                    "region" => "testRegion",
                    "postcode" => "testPostcode",
                    "country_id" => "id",
                    "telephone" => "0123456789",
                    "fax" => "0123456789",
                    "is_default_shipping" => 0,
                    "is_default_billing" => 0
                )
            );
        }
        
        $result = $this->soapClient->call($this->soapSession, "cart_customer.addresses", array($this->cartId, $address));
        return $result;
    }

    /**
     * Parse cart view template
     *
     * @return void
     */
    public function parse() {
        // Rendering items
        $renderedItems = '';
        if(count($this->items) > 0) {
            foreach ($this->items as $item) {
                $item->parse();
                $renderedItems .= $item->getView()->render();
            }
        }
        
        $this->view->assign('##CART_ITEMS##', $renderedItems);
        $this->view->assign('##CART_TOTAL_PRICE##', number_format($this->totalPrice, 2, ',', ' '));
        $this->view->assign('##TRANSFERT_CART_URL##', OB_TRANSFERT_CART_URL . '?quoteId=' . $this->cartId);
    }

    /**
     * Returns cart items
     *
     * @return array
     */
    public function getItems() {
        return $this->items;
    }
    
}
?>