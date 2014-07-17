<?php
namespace Magento\Tools\I18n\Mage1\Code;
use Magento\Tools\I18n\Code as Mage2;
use Magento\Tools\I18n\Mage1\Code as Mage1;

/**
 *  Service Locator (instead DI container)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ServiceLocator extends Mage2\ServiceLocator
{
    /**
     * Domain abstract factory
     *
     * @var \Magento\Tools\I18n\Code\Factory
     */
    protected static $_factory;

    /**
     * Context manager
     *
     * @var \Magento\Tools\I18n\Mage1\Code\Context
     */
    protected static $_context;

    /**
     * Dictionary generator
     *
     * @var \Magento\Tools\I18n\Code\Dictionary\Generator
     */
    protected static $_dictionaryGenerator;

    /**
     * Pack generator
     *
     * @var \Magento\Tools\I18n\Code\Pack\Generator
     */
    protected static $_packGenerator;

    /**
     * Get dictionary generator
     *
     * @return \Magento\Tools\I18n\Code\Dictionary\Generator
     */
    public static function getDictionaryGenerator()
    {
        if (null === self::$_dictionaryGenerator) {
            $filesCollector = new Mage2\FilesCollector();

            $phraseCollector = new Mage2\Parser\Adapter\Php\Tokenizer\PhraseCollector(
                new Mage2\Parser\Adapter\Php\Tokenizer()
            );
            $adapters = array(
                'php' => new Mage2\Parser\Adapter\Php($phraseCollector),
                'js' => new Mage2\Parser\Adapter\Js(),
                'xml' => new Mage2\Parser\Adapter\Xml()
            );

            $parser = new Mage2\Parser\Parser($filesCollector, self::_getFactory());

            //use Mage1 Parser\Contextual
            $parserContextual = new Mage1\Parser\Contextual($filesCollector, self::_getFactory(), self::_getContext());
            foreach ($adapters as $type => $adapter) {
                $parser->addAdapter($type, $adapter);
                $parserContextual->addAdapter($type, $adapter);
            }

            self::$_dictionaryGenerator = new Mage2\Dictionary\Generator(
                $parser, $parserContextual, self::_getFactory()
            );
        }
        return self::$_dictionaryGenerator;
    }

    /**
     * Get factory
     *
     * @return \Magento\Tools\I18n\Code\Factory
     */
    protected static function _getFactory()
    {
        if (null === self::$_factory) {
            self::$_factory = new Mage2\Factory();
        }
        return self::$_factory;
    }

    /**
     * Get context
     *
     * @return \Magento\Tools\I18n\Code\Context
     */
    protected static function _getContext()
    {
        if (null === self::$_context) {
            //use Mage1 Context
            self::$_context = new Mage1\Context();
        }
        return self::$_context;
    }
}
