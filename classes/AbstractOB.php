<?php
/**
 * Abstract OB class
 */
abstract class AbstractOB {
    
    /**
     * The OB store view ID
     * @var int
     */
    protected $storeView = NULL;
    
    /**
     * The SOAP client
     * @var \SoapClient
     */
    protected $soapClient;
    
    /**
     * The SOAP session ID
     * @var int
     */
    protected $soapSession;
    
    /**
     * Template view
     * @var OBTemplate
     */
    protected $view;
    

    /**
     * Constructor
     * @param string $storeView Name of the magento store view
     */
    public function __construct() {
        session_start();
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
     * Return view
     *
     * @return OBTemplate
     */
    public function getView() {
        return $this->view;   
    }
}
?>