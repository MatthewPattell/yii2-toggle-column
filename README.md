Active Record column toggle in GridView for Yii2
===========================
Simple widget for toggle model column in grid view

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist matthew-p/yii2-toggle-column "@dev"
```

or add

```
"matthew-p/yii2-toggle-column": "@dev"
```

to the require section of your `composer.json` file.

Usage
-----

Once the extension is installed, simply use it in your code by:

Add toggle column to gridview:
```php
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'title',
        // Toggle column
        [
            'class'      => \MP\GridView\ToggleColumn::class,
            'attribute'  => 'active',
            'values'     => [
                'value1' => 'Published',
                'value2' => 'Unpublished',
            ],
        ],
        [
            'attribute' => 'created_at',
            'format'    => ['date', 'format' => 'php: d/m/Y H:i:s'],
        ],

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
```

Add action in controller:
```php
class SampleController extends Controller
{
...
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'mp-toggle-column' => \MP\GridView\ToggleColumnAction::class,
        ]);
    }
...
}
```

Define encryption key in params.php (if not defined):
```
'MPComponents' => [
    'encryptionKey' => 'RandomKey',
],
```

That's all. Check it.