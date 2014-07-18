mage1-translate
=======================

Adapter for Magento 1 to use Translate Tool to get translation lines from the code
To make a dictionary please use following command:
{code}
php Magento/Tools/I18n/generator-mage1.php \
    --directory d:/home/project \
    --output d:/project.csv \
    --package enterprise \
    --theme project \
    --magento Y
{code}
To update your locale files please use following command:
{code}
php Magento/Tools/I18n/pack-mage1.php \
    --source d:/project.csv \
    --mode merge \
    --locale en_US
{code}
