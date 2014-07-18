<?php
namespace Magento\Tools\I18n\Mage1\Code;
use Magento\Tools\I18n\Code as Mage2;

/**
 *  Abstract Factory for Magento 1
 */
class Factory extends Mage2\Factory
{
    /**
     * Create locale for Magento 1
     *
     * @param string $locale
     * @return \Magento\Tools\I18n\Code\Locale
     */
    public function createLocale($locale)
    {
        return new Locale($locale);
    }
}
