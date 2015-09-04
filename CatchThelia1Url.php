<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace CatchThelia1Url;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\ModuleConfig;
use Thelia\Model\ModuleQuery;
use Thelia\Module\BaseModule;
use \Thelia\Model\ModuleConfigQuery;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class CatchThelia1Url extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'catchthelia1url';

    /** @var int */
    static protected $moduleId = null;

    /** @var array */
    static protected $cache = [];

    /** @var array */
    static public $defaultConfig = array(
        'productImageWidth' => 1080,
        'productImageHeight' => 608,
        'categoryImageWidth' => 1080,
        'categoryImageHeight' => 608,
        'contentImageWidth' => 1080,
        'contentImageHeight' => 608,
        'folderImageWidth' => 1080,
        'folderImageHeight' => 608
    );

    /**
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        if (!self::getConfigValue('is_initialized', false)) {
            foreach (static::$defaultConfig as $name => $value) {
                self::setConfigValue($name, $value);
            }

            self::setConfigValue('is_initialized', true);
        }
    }

    protected static function init()
    {
        if (!static::$moduleId === null) {
            static::$cache = static::$defaultConfig;

            static::$moduleId = ModuleQuery::create()->findOneByCode('CatchThelia1Url')->getId();

            $caches = ModuleConfigQuery::create()
                ->joinWithI18n()
                ->findOneByModuleId(static::$moduleId);

            /** @var ModuleConfig $cache */
            foreach ($caches as $cache) {
                static::$cache[$cache->getName()] = $cache->getVirtualColumn('i18n_VALUE');
            }
        }
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return string|mixed
     */
    public static function getConfig($name, $default = null)
    {
        static::init();

        return (isset(static::$cache[$name])) ? static::$cache[$name] : $default;
    }
}
