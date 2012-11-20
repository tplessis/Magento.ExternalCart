<?php
/**
 * Abstract class template.
 *
 */
abstract class AbstractOBTemplate {

    /**
     * Source code of the template
     * @var string
     */
    protected $templateSource = NULL;

    /**
     * Absolute path of the template
     * @var string
     */
    protected $templatePathAndFilename = NULL;
    
    /**
     * Replace given tag with given var
     *
     * @param string $tag Tag to replace
     * @param string $var Value to replace with
     * @return void
     */
    public function assign($tag, $var) {
        $this->templateSource = str_replace($tag, $var, $this->templateSource);
    }
    
    /**
     * Render template
     *
     * @return string
     */
    public function render() {
        return $this->templateSource;
    }
    
    /**
     * Loading template
     *
     * @return void
     */
    public function loadTemplate() {
        if (!file_exists($this->templatePathAndFilename)) {
            throw new Exception('Template file called ' .  $this->templatePathAndFilename. ' does not exists.');
        }
            
        $this->templateSource = file_get_contents($this->templatePathAndFilename);
    } 
}
?>