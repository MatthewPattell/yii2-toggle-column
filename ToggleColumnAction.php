<?php
/**
 * Created by PhpStorm.
 * User: Yarmaliuk Mikhail
 * Date: 07.05.18
 * Time: 20:07
 */

namespace MP\GridView;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class    ToggleColumnAction
 * @package MP\GridView
 * @author  Yarmaliuk Mikhail
 * @version 1.0
 */
class ToggleColumnAction extends Action
{
    /**
     * Toggle model attribute
     *
     * @return array
     */
    public function run(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $encryptionKey = NULL;
        $result        = false;

        if (!empty(Yii::$app->params['MPComponents']['encryptionKey'])) {
            $encryptionKey = Yii::$app->params['MPComponents']['encryptionKey'];
        } else {
            throw new InvalidConfigException('Required `encryptionKey` param isn\'t set (in `MPComponents`).');
        }

        $data = Yii::$app->getSecurity()->decryptByKey(\base64_decode(Yii::$app->request->post('mpDataARToggle')), $encryptionKey);

        if (empty($data) || empty($data = json_decode($data, true))) {
            throw new NotFoundHttpException();
        }

        $values  = $data['values'];
        $modelID = Yii::$app->request->post('id');
        $value   = $this->getNextValue(Yii::$app->request->post('value'), $values);

        if ($value !== NULL) {
            /** @var ActiveRecord $modelClassName */
            $modelClassName = $data['modelClass'];
            $primaryKey     = $data['primaryKey'];
            $model          = $modelClassName::find()
                ->where([$primaryKey => $modelID])
                ->one();

            if ($model instanceof ActiveRecord) {
                $model->setAttribute($data['attribute'], $value);
                $result = $model->save();
            }
        }

        return [
            'result' => $result,
            'value'  => $value,
        ];
    }

    /**
     * Get next value
     *
     * @param string $value
     * @param array  $values
     *
     * @return string|NULL
     */
    private function getNextValue($value, array $values)
    {
        $currPos = \array_search($value, $values);

        if ($currPos !== false) {
            if (isset($values[$currPos + 1])) {
                return $values[$currPos + 1];
            } else {
                return $values[0];
            }
        }

        return NULL;
    }
}