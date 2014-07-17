<?php
namespace Magento\Tools\I18n\Mage1\Code;
use Magento\Tools\I18n\Code as Mage2;

/**
 *  Context
 */
class Context extends Mage2\Context
{
    /**
     * Get context from file path in array(<context type>, <context value>) format
     * - for module: <Namespace>_<module name>
     * - for theme: <area>/<theme name>
     * - for pub: relative path to file
     *
     * @param string $path
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getContextByPath($path)
    {
        if ($value = strstr($path, '/app/code/')) {
            $type = self::CONTEXT_TYPE_MODULE;
            $value = explode('/', $value);
            $value = $value[4] . '_' . $value[5];  //updated for Mage1 modules structure
        } elseif ($value = strstr($path, '/app/design/')) {
            $type = self::CONTEXT_TYPE_THEME;
            $value = explode('/', $value);
            //updated for Mage1 modules structure
            $value = $value[3] . '/' . $value[4] . '/' . $value[5] . '/' . $value[6];
        } elseif ($value = strstr($path, '/lib/')) {
            $type = self::CONTEXT_TYPE_PUB;
            $value = ltrim($value, '/');
        } else {
            throw new \InvalidArgumentException(sprintf('Invalid path given: "%s".', $path));
        }
        return array($type, $value);
    }
}
