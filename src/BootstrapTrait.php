<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2018
 * @version   1.9.3
 */

namespace kartik\base;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;

/**
 * BootstrapTrait includes bootstrap library init and parsing methods
 *
 * @property string $bsVersion
 * @property string $_defaultIconPrefix
 * @property string $_defaultBtnCss
 * @property bool $_isBs4
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
trait BootstrapTrait
{
    /**
     * Initializes bootstrap versions for the widgets and asset bundles.
     * Sets the [[_isBs4]] flag by parsing [[bsVersion]]
     *
     * @throws InvalidConfigException
     */
    protected function initBsVersion()
    {
        $v = empty($this->bsVersion) ? ArrayHelper::getValue(Yii::$app->params, 'bsVersion', '3.x') : $this->bsVersion;
        $this->_isBs4 = static::parseVer($v) === '4';
        $this->_defaultIconPrefix = 'glyphicon glyphicon-';
        $this->_defaultBtnCss = 'btn-default';
        if ($this->_isBs4) {
            if (!class_exists('yii\bootstrap4\Html')) {
                throw new InvalidConfigException(
                    "You must install 'yiisoft/yii2-bootstrap4' extension for Bootstrap 4.x version support. " .
                    "Dependency to 'yii2-bootstrap4' has not been included with 'yii2-krajee-base'. To resolve, you " .
                    "must add 'yiisoft/yii2-bootstrap4' to the 'require' section of your application's composer.json " .
                    "and then run 'composer update'."
                );
            }
            $this->_defaultIconPrefix = 'fas fa-';
            $this->_defaultBtnCss = 'btn-outline-secondary';
        }
    }

    /**
     * Validate if Bootstrap 4.x version
     * @return bool
     *
     * @throws InvalidConfigException
     */
    public function isBs4()
    {
        if (!isset($this->_isBs4)) {
            $this->initBsVersion();
        }
        return $this->_isBs4;
    }

    /**
     * Gets the default button CSS
     * @return string
     */
    public function getDefaultBtnCss()
    {
        return $this->_defaultBtnCss;
    }

    /**
     * Gets the default icon css prefix
     * @return string
     */
    public function getDefaultIconPrefix()
    {
        return $this->_defaultIconPrefix;
    }

    /**
     * Parses and returns the major BS version
     * @param string $ver
     * @return bool|string
     */
    protected static function parseVer($ver)
    {
        $ver = (string)$ver;
        return substr(trim($ver), 0, 1);
    }

    /**
     * Compares two versions and checks if they are of the same major BS version
     * @param int|string $ver1 first version
     * @param int|string $ver2 second version
     * @return bool whether major versions are equal
     */
    protected static function isSameVersion($ver1, $ver2)
    {
        if ($ver1 === $ver2 || (empty($ver1) && empty($ver2))) {
            return true;
        }
        return static::parseVer($ver1) === static::parseVer($ver2);
    }
}
