<?php
namespace Magento\Tools\I18n\Mage1\Code\Parser\Adapter;
use Magento\Tools\I18n\Code\Parser\Adapter as Mage2;
/**
 * Xml parser adapter for Magento 1
 *
 * Parse "translate" node and collect phrases:
 * - from itself, it @translate == true
 * - from given attributes, split by ",", " "
 */
class Xml extends Mage2\Xml
{
    /**
     * {@inheritdoc}
     */
    protected function _parse()
    {
        //copy-pasted code
        foreach ($this->_getNodes($this->_file) as $element) {
            if (!$element instanceof \SimpleXMLElement) {
                continue;
            }
            $attributes = $element->attributes();
            if ((string)$attributes['translate'] == 'true') {
                $this->_addPhrase((string)$element);
            } else {
                //BOF updated code
                $nodesDelimiter = strpos($attributes['translate'], ' ') === false ? ',' : ' ';
                foreach (explode($nodesDelimiter, $attributes['translate']) as $value) {
                    //EOF updated code
                    $phrase = (string)$element->{$value};
                    if ($phrase) {
                        $this->_addPhrase($phrase);
                    }
                }
            }
        }
    }
}

