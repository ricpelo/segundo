<?php

namespace app\models;

/**
 * This is the model class for table "especialidades".
 *
 * @property int $id
 * @property string $especialidad
 *
 * @property Especialistas[] $especialistas
 */
class Especialidades extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'especialidades';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['especialidad'], 'required'],
            [['especialidad'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'especialidad' => 'Especialidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEspecialistas()
    {
        return $this->hasMany(Especialistas::className(), ['especialidad_id' => 'id'])->inverseOf('especialidad');
    }

    public static function lista()
    {
        return static::find()->select('especialidad')->indexBy('id')->column();
    }
}
