<?php
namespace Magento\Tools\I18n\Mage1\Code;
use Magento\Tools\I18n\Code as Mage2;

/**
 *  Abstract Factory
 */
class Locale extends Mage2\Locale
{
    public function __construct($locale)
    {
        /**
         * Here removed checking default locale
         *
         * @see \Magento\Tools\I18n\Code\Locale::__construct()
         */
        if (!preg_match('/[a-z]{2}_[A-Z]{2}/', $locale)) {
            throw new \InvalidArgumentException('Target locale must match the following format: "aa_AA".');
        }
        $this->_locale = $locale;
    }
}
