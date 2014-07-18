<?php
namespace Magento\Tools\I18n\Mage1\Code\Pack\Writer\File;
use Magento\Tools\I18n\Code\Pack\Writer\File as Mage2;
use Magento\Tools\I18n\Code\Dictionary;
use Magento\Tools\I18n\Mage1\Code\Context;

/**
 * Csv writer
 */
class Csv extends Mage2\Csv
{

    /**
     * Custom locale dir
     *
     * @var string
     */
    protected $_customLocaleDir;

    /**
     * Build pack files data
     *
     * @param Dictionary $dictionary
     * @return array
     * @throws \RuntimeException
     */
    protected function _buildPackFilesData(Dictionary $dictionary)
    {
        $files = array();
        foreach ($dictionary->getPhrases() as $key => $phrase) {
            if (!$phrase->getContextType() || !$phrase->getContextValue()) {
                throw new \RuntimeException(sprintf('Missed context in row #%d.', $key + 1));
            }
            foreach ($phrase->getContextValue() as $context) {
                $path = $this->_context->buildPathToLocaleDirectoryByContext($phrase->getContextType(), $context);
                $filename = $this->_buildLocaleFilename($path, $phrase->getContextType(), $context);
                $files[$filename][$phrase->getPhrase()] = $phrase;
            }
        }
        return $files;
    }

    /**
     * Build locale filename
     *
     * @param string $path
     * @param string $type
     * @param string $value
     * @return string
     */
    protected function _buildLocaleFilename($path, $type, $value)
    {
        switch ($type) {
            case Context::CONTEXT_TYPE_MODULE:
                $path =  $this->_packPath . $path . $this->_locale . '/' . $value . '.' . $this->_getFileExtension();
                break;

            case Context::CONTEXT_TYPE_THEME:
                $path =  $this->_packPath . $path . $this->_locale . '/' . 'Translate.' . $this->_getFileExtension();
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid context given: "%s".', $type));
        }
        return $path;
    }
}
