<?php

namespace app\models;

/**
 * This is the model class for table "citas".
 *
 * @property int $id
 * @property int $usuario_id
 * @property int $especialista_id
 * @property string $instante
 *
 * @property Especialistas $especialista
 * @property Usuarios $usuario
 */
class Citas extends \yii\db\ActiveRecord
{
    public $especialidad_id;
    public $instante_formateado;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'citas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'especialista_id', 'instante'], 'required'],
            [['usuario_id', 'especialista_id'], 'default', 'value' => null],
            [['usuario_id', 'especialista_id'], 'integer'],
            [['especialista_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especialistas::className(), 'targetAttribute' => ['especialista_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['especialista_id'], 'especialidadDuplicada'],
        ];
    }

    public function especialidadDuplicada($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $especialista = Especialistas::findOne($this->especialista_id);
            $especialidad_id = $especialista->especialidad_id;
            if (static::find()
                ->joinWith('especialista e')
                ->where([
                    'usuario_id' => $this->usuario_id,
                    'e.especialidad_id' => $especialidad_id,
                ])
                ->andWhere('instante > LOCALTIMESTAMP')
                ->exists()) {
                $this->addError($attribute, 'No puedes tener dos citas con la misma especialidad');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'especialidad_id' => 'Especialidad',
            'especialista_id' => 'Especialista',
            'instante' => 'Fecha y hora',
            'instante_formateado' => 'Fecha y hora',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEspecialista()
    {
        return $this->hasOne(Especialistas::className(), ['id' => 'especialista_id'])->inverseOf('citas');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('citas');
    }
}
