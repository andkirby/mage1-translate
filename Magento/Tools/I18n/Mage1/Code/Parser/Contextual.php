<?php
namespace Magento\Tools\I18n\Mage1\Code\Parser;
use Magento\Tools\I18n\Mage1\Code\Parser as Mage1;
use Magento\Tools\I18n\Code\Parser as Mage2;

use Magento\Tools\I18n\Code;

/**
 * Contextual Parser
 */
class Contextual extends Mage2\Contextual
{
    /**
     * Add phrase with context
     *
     * @param array $phraseData
     * @param string $contextType
     * @param string $contextValue
     * @return void
     */
    protected function _addPhrase($phraseData, $contextType, $contextValue)
    {
        //added checking empty phrase for Mage1
        if (!empty($phraseData['phrase'])) {
            parent::_addPhrase($phraseData, $contextType, $contextValue);
        }
    }
}
