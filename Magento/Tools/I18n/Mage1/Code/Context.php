<?php
namespace Magento\Tools\I18n\Mage1\Code;
use Magento\Tools\I18n\Code as Mage2;

/**
 *  Context
 */
class Context extends Mage2\Context
{
    /**
     * Locale directory for Magento 1
     */
    const LOCALE_DIRECTORY = 'locale';

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

    /**
     * Get paths by context for Magento 1
     *
     * @param string $type
     * @param array $value
     * @return string
     * @throws \InvalidArgumentException
     */
    public function buildPathToLocaleDirectoryByContext($type, $value)
    {
        switch ($type) {
            case self::CONTEXT_TYPE_MODULE:
                $path = $this->_getGlobalLocalePath();
                break;

            case self::CONTEXT_TYPE_THEME:
                $path = $this->_getThemeLocalePath($value);
                break;

            case self::CONTEXT_TYPE_PUB:
                throw new \InvalidArgumentException(sprintf('Context type "%s" is not supported.', $type));

            default:
                throw new \InvalidArgumentException(sprintf('Invalid context given: "%s".', $type));
        }
        return $path;
    }

    /**
     * Get project relative theme path
     *
     * @param string $value
     * @return string
     */
    protected function _getThemeLocalePath($value)
    {
        return 'app/design/' . $this->_getThemePath($value) . '/' . self::LOCALE_DIRECTORY . '/';
    }

    /**
     * Get relative theme path
     *
     * @param string $value
     * @return string
     */
    protected function _getThemePath($value)
    {
        $arr = explode('/', $value);
        array_pop($arr);
        return trim(implode('/', $arr), '/');
    }

    /**
     * Get global locale path in Magento 1
     *
     * @return string
     */
    protected function _getGlobalLocalePath()
    {
        return 'app/' . self::LOCALE_DIRECTORY . '/';
    }
}
