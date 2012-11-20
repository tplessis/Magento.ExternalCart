<?php
require_once(__DIR__ . '/AbstractOBTemplate.php');

/**
 * Cart template class
 *
 */
class OBTemplate extends AbstractOBTemplate {
    
     /**
     * Constructor
     * 
     */
    public function __construct($templatePathAndFilename) {
        $this->templatePathAndFilename = $templatePathAndFilename;
        $this->loadTemplate();
    }
    
}
?>