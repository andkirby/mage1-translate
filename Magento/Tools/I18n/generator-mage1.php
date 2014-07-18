<?php
require_once __DIR__ . '/bootstrap.php';
use Magento\Tools\I18n\Mage1\Code\ServiceLocator;

try {
    $console = new \Zend_Console_Getopt(
        array(
            'package|p=s' => 'Design package name. Default: "enterprise"',    //custom option
            'theme|t=s' => 'Design theme name.',                              //custom option
            'codepool|c=s' => 'Code pools list. Default: "community|local"',  //custom option
            'directory|d=s' => 'Path to base directory for parsing',
            'output|o=s' => 'Path(with filename) to output file, '.
            'by default output the results into standard output stream',
            'magento|m=s' => 'Indicates whether directory for parsing is Magento directory, "no" by default'
        )
    );
    $console->parse();

    $directory      = $console->getOption('directory') ?: null;
    $package        = $console->getOption('package') ?: 'enterprise';
    $theme          = $console->getOption('theme') ?: null;
    $codePools      = $console->getOption('codepool') ?: 'community|local';
    $outputFilename = $console->getOption('output') ?: null;
    $isMagento = in_array($console->getOption('magento'), array('y', 'yes', 'Y', 'Yes', 'YES'));

    echo <<<REPORT
Directory       : $directory
Package         : $package
Theme           : $theme
Output filename : $outputFilename
Code pools      : $codePools

REPORT;

    if (!$directory) {
        throw new \InvalidArgumentException('Directory parameter is required.');
    }
    if (!$theme) {
        throw new \InvalidArgumentException('Theme parameter is required.');
    }
    if (!$package) {
        throw new \InvalidArgumentException('Package parameter is required.');
    }

    if ($isMagento) {
        $directory = rtrim($directory, '\\/');
        $designPath   = $directory . '/app/design/frontend/' . $package . '/' . $theme . '/';
        $filesOptions = array(
            array(
                'type' => 'php',
                'paths' => array(
                    $designPath
                ),
                'fileMask' => '/\.(php|phtml)$/'
            ),
            array(
                'type' => 'js',
                'paths' => array(
                    $designPath,
                ),
                'fileMask' => '/\.(js|phtml)$/'
            ),
            array(
                'type' => 'xml',
                'paths' => array(
                    $designPath
                ),
                'fileMask' => '/\.xml$/'
            )
        );

        //set code pools
        $codePools = explode('|', $codePools);
        $phpCodePools = array();
        foreach ($codePools as $pool) {
            $phpCodePools[] = $directory . '/app/code/' . $pool . '/';
        }
        foreach ($filesOptions as &$item) {
            $item['paths'] = array_merge($phpCodePools, $item['paths']);
        }
    } else {
        $filesOptions = array(
            array('type' => 'php', 'paths' => array($directory), 'fileMask' => '/\.(php|phtml)$/'),
            array('type' => 'js', 'paths' => array($directory), 'fileMask' => '/\.(js|phtml)$/'),
            array('type' => 'xml', 'paths' => array($directory), 'fileMask' => '/\.xml$/')
        );
    }

    $generator = ServiceLocator::getDictionaryGenerator();
    $generator->generate($filesOptions, $outputFilename, $isMagento);

    fwrite(STDOUT, PHP_EOL . 'Dictionary successfully processed.' . PHP_EOL);
} catch (\Zend_Console_Getopt_Exception $e) {
    fwrite(STDERR, PHP_EOL . $e->getUsageMessage() . PHP_EOL);
    exit(1);
} catch (\Exception $e) {
    fwrite(STDERR, PHP_EOL . 'Translate phrase generator failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
