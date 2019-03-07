<?php

namespace app\models;

/**
 * This is the model class for table "especialistas".
 *
 * @property int $id
 * @property string $nombre
 * @property int $especialidad_id
 * @property string $hora_minima
 * @property string $hora_maxima
 * @property string $duracion
 *
 * @property Citas[] $citas
 * @property Especialidades $especialidad
 */
class Especialistas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'especialistas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'especialidad_id', 'hora_minima', 'hora_maxima', 'duracion'], 'required'],
            [['especialidad_id'], 'default', 'value' => null],
            [['especialidad_id'], 'integer'],
            [['hora_minima', 'hora_maxima'], 'safe'],
            [['duracion'], 'string'],
            [['nombre'], 'string', 'max' => 255],
            [['especialidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Especialidades::className(), 'targetAttribute' => ['especialidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'especialidad_id' => 'Especialidad ID',
            'hora_minima' => 'Hora Minima',
            'hora_maxima' => 'Hora Maxima',
            'duracion' => 'Duracion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCitas()
    {
        return $this->hasMany(Citas::className(), ['especialista_id' => 'id'])->inverseOf('especialista');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEspecialidad()
    {
        return $this->hasOne(Especialidades::className(), ['id' => 'especialidad_id'])->inverseOf('especialistas');
    }

    public static function lista($especialidad_id)
    {
        return static::find()
            ->select('nombre')
            ->where(['especialidad_id' => $especialidad_id])
            ->indexBy('id')
            ->column();
    }
}
