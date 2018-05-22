<?php
/**
 * Created by PhpStorm.
 * User: Yarmaliuk Mikhail
 * Date: 21.05.18
 * Time: 22:18
 */

namespace MP\GridView;

use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class    ToggleColumnAsset
 * @package MP\GridView
 * @author  Yarmaliuk Mikhail
 * @version 1.0
 */
class ToggleColumnAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = __DIR__ . '/assets';

    /**
     * @var array
     */
    public $js = [
        'mp-toggle-column.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
    ];
}