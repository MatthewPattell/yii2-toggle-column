<?php
/**
 * Created by PhpStorm.
 * User: Yarmaliuk Mikhail
 * Date: 21.05.18
 * Time: 22:15
 */

namespace MP\GridView;

use Yii;
use yii\base\InvalidConfigException;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Class    ToggleColumn
 * @package MP\GridView
 * @author  Yarmaliuk Mikhail
 * @version 1.0
 */
class ToggleColumn extends DataColumn
{
    /**
     * Values
     *
     * value => button
     *
     * @var array|\Closure
     */
    public $values = [
        0 => 'Unpublished',
        1 => 'Published',
    ];

    /**
     * @var string
     */
    public $actionUrl = 'mp-toggle-column';

    /**
     * @var string
     */
    public $format = 'raw';

    /**
     * Model class name
     *
     * @var string|NULL
     */
    public $modelClass = NULL;

    /**
     * @var string
     */
    private $encryptionKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!empty(Yii::$app->params['MPComponents']['encryptionKey'])) {
            $this->encryptionKey = Yii::$app->params['MPComponents']['encryptionKey'];
        } else {
            throw new InvalidConfigException('Required `encryptionKey` param isn\'t set (in `MPComponents`).');
        }

        if (empty($this->modelClass)) {
            $this->modelClass = \get_class($this->grid->filterModel);
        }

        if (empty($this->grid->options['id'])) {
            $this->grid->options['id'] = $this->getUniqueId();
        }

        if (empty($this->filter) && $this->filter !== false && $this->filter !== NULL) {
            $this->filter = array_map(function ($v) {
                return trim(strip_tags($v));
            }, $this->values);
        }

        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $primaryKey = $modelClass::primaryKey()[0];

        $localModuleOptions = [
            'url'            => $this->actionUrl,
            'values'         => $this->values,
            'mpDataARToggle' => \base64_encode(Yii::$app->getSecurity()->encryptByKey(json_encode([
                'modelClass' => $modelClass,
                'attribute'  => $this->attribute,
                'primaryKey' => $primaryKey,
                'values'     => \array_keys($this->values),
            ]), $this->encryptionKey)),
        ];

        $this->registerAssets($localModuleOptions);
    }

    /**
     * Register assets
     *
     * @param array $localModuleOptions
     *
     * @return void
     */
    protected function registerAssets(array $localModuleOptions = []): void
    {
        ToggleColumnAsset::register($this->grid->view);

        $this->grid->view->registerCss('.mp-toggle-button{display:inline-block;cursor:pointer;}.tg-loading{opacity:0.7;}');

        $this->grid->view->registerJs('MPToggleColumn.init();', View::POS_END, 'MPToggleColumnInit');
        $this->grid->view->registerJs("MPToggleColumn.add('.mp-toggle-button', " . Json::encode($localModuleOptions) . ");");
    }

    /**
     * Get grid unique class
     *
     * @return string
     */
    private function getUniqueId(): string
    {
        return 'mp-grid-' . \rand(10000, 99999);
    }

    /**
     * @inheritdoc
     */
    public function renderDataCellContent($model, $key, $index)
    {
        return Html::tag('div', $this->values[$model->{$this->attribute}], [
            'class'      => 'mp-toggle-button',
            'data-id'    => $key,
            'data-value' => $model->{$this->attribute},
        ]);
    }
}