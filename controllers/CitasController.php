<?php

namespace app\controllers;

use app\models\Citas;
use app\models\CitasSearch;
use app\models\Especialidades;
use app\models\Especialistas;
use DateInterval;
use DateTime;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CitasController implements the CRUD actions for Citas model.
 */
class CitasController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Citas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Citas model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Citas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Citas(['usuario_id' => Yii::$app->user->id]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $especialidades = Especialidades::lista();
        $especialistas = !empty($especialidades)
            ? Especialistas::lista(key($especialidades))
            : [];

        return $this->render('create', [
            'model' => $model,
            'especialidades' => $especialidades,
            'especialistas' => $especialistas,
        ]);
    }

    /**
     * Updates an existing Citas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Citas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionEspecialistasAjax($especialidad_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Especialistas::find()
            ->select('id, nombre')
            ->where(['especialidad_id' => $especialidad_id])
            ->orderBy('nombre')
            ->asArray()
            ->all();
    }

    public function actionHuecoAjax($especialista_id)
    {
        $especialista = Especialistas::findOne($especialista_id);
        $hora_minima = $especialista->hora_minima;
        $hora_maxima = $especialista->hora_maxima;
        $ahora = new DateTime();
        $duracion = new DateInterval($especialista->duracion);
        $instante = new DateTime(date('Y-m-d') . ' ' . $hora_minima);
        for (;;) {
            if ($instante <= $ahora || Citas::find()
                ->where([
                    'especialista_id' => $especialista_id,
                    'instante' => $instante->format('Y-m-d H:i:s'),
                ])->exists()) {
                $instante->add($duracion);
                $maximo = new DateTime($instante->format('Y-m-d') . ' ' . $hora_maxima);
                if ($instante >= $maximo) {
                    $instante->add(new DateInterval('P1D'));
                    $instante = new DateTime($instante->format('Y-m-d') . ' ' . $hora_minima);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'valor' => $instante->format('Y-m-d H:i:s'),
                    'formateado' => Yii::$app->formatter->asDatetime($instante),
                ];
            }
        }
    }

    /**
     * Finds the Citas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Citas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Citas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
