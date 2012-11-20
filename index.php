<?php
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/classes/AbstractOBTemplate.php');
require_once(__DIR__ . '/classes/OBCart.php');
require_once(__DIR__ . '/classes/OBCartItem.php');

$action = NULL;
if(isset($_GET['action'])) $action = $_GET['action'];

try {
    $obCart = new OBCart(OB_STORE_VIEW);
    
    switch ($action) {
        case 'add':
            $sku = NULL;
            if(isset($_GET['sku'])) $sku = $_GET['sku'];
            $obCart->addItem($sku, 1);
            header('Location: '.$_SERVER['PHP_SELF']);
            break;
        case 'update':
            $id = NULL;
            $quantity = 1;
            if(isset($_GET['id'])) $id = $_GET['id'];
            if(isset($_GET['quantity'])) $quantity = $_GET['quantity'];
            echo $obCart->updateItem($id, $quantity);
            exit;
            break;
        case 'remove':
            $sku = NULL;
            if(isset($_GET['sku'])) $sku = $_GET['sku'];
            $obCart->removeItem($sku);
            header('Location: '.$_SERVER['PHP_SELF']);
            break;
        default:
            break;
    }
    
    $obCart->loadItems();
    $obCart->parse();
    echo $obCart->getView()->render();
} catch (Exception $error) {
    echo $error->getMessage().'<br />';
    echo $error->getTraceAsString();
}
?>