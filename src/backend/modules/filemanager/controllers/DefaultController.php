<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager\controllers;

use InvalidArgumentException;
use mobilejazz\yii2\cms\backend\modules\filemanager\assets\FilemanagerAsset;
use mobilejazz\yii2\cms\backend\modules\filemanager\models\Mediafile;
use mobilejazz\yii2\cms\common\AuthHelper;
use mobilejazz\yii2\cms\common\models\User;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use ZipArchive;

class DefaultController extends Controller
{

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = false;


    /**
     * Checks if a user is Allowed to see this content.
     *
     * @param User $user
     *
     * @return boolean
     */
    public static function isAllowed(User $user)
    {
        return $user->role === User::ROLE_ADMIN || $user->role === User::ROLE_EDITOR;
    }


    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'post' ],
                    'update' => [ 'post' ],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'         => false,
                        'matchCallback' => function ($rule, $action)
                        {
                            $writeLockConfig = ArrayHelper::getValue(\Yii::$app->params, 'writeLock', ['production']);
                            return array_key_exists(\Yii::$app->params[ 'environment' ], $writeLockConfig);
                        },
                        'denyCallback'  => AuthHelper::denyCallback(function ()
                        {
                            Yii::$app->session->setFlash('error',
                                Yii::t('backend', 'Sorry, you can not access this page in a PRODUCTION ENVIRONMENT.'));
                        }),
                    ],
                    [
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $model                                     = new Mediafile();
        $dataProvider                              = $model->search();
        $dataProvider->pagination->defaultPageSize = 15;

        return $this->render('index', [
            'model'        => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionSettings()
    {
        return $this->render('settings');
    }


    public function actionUploadmanagerContent()
    {
        $this->layout = 'main';

        return $this->render('uploadmanager-content', [ 'model' => new Mediafile() ]);
    }


    public function actionUploader()
    {
        return $this->render('uploader');
    }


    public function actionFileManagerModalContent()
    {
        $this->layout                              = 'main';
        $model                                     = new Mediafile();
        $dataProvider                              = $model->search();
        $dataProvider->pagination->defaultPageSize = 15;

        if (Yii::$app->request->isAjax)
        {
            return $this->renderAjax('modal-content', [
                'model'        => $model,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('modal-content', [
            'model'        => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @throws yii\base\Exception
     * @throws yii\web\ForbiddenHttpException
     */
    public function actionDownloadFiles()
    {
        /** @var User $user */
        $user = \Yii::$app->user->getIdentity();

        if (isset($user) && $user->role === User::ROLE_ADMIN)
        {
            try
            {

                $tmp_folder = $this->createTempFolder();
                $this->extractDB($tmp_folder);
                $path = realpath(Yii::getAlias("@backend/web/files"));
                $this->copyMedia($path . "/.", $tmp_folder);
                $this->makeZipFile($tmp_folder);

                $file_path = $tmp_folder . '/files.zip';
                ob_end_clean();
                \Yii::$app->response->sendFile($file_path);

            }
            catch (yii\base\Exception $e)
            {
                throw $e;
            }
        }

        else
        {
            throw new yii\web\ForbiddenHttpException;
        }
    }


    private function createTempFolder()
    {
        $tmp_folder = '/tmp/' . \Yii::$app->params[ 'baseUrl' ];
        $tmp_folder = str_replace('http://', '', $tmp_folder);
        $this->removeDirectory($tmp_folder);
        mkdir($tmp_folder);

        return $tmp_folder;
    }


    private function removeDirectory($dirPath)
    {
        if (file_exists($dirPath))
        {
            if (!is_dir($dirPath))
            {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/')
            {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file)
            {
                if (is_dir($file))
                {
                    $this->removeDirectory($file);
                }
                else
                {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
    }


    private function extractDB($tmp_folder)
    {
        // DATABASE DUMP
        $mysqlDatabaseName = \Yii::$app->db->username;
        $mysqlUserName     = \Yii::$app->db->username;
        $mysqlPassword     = \Yii::$app->db->password;
        $mysqlExportPath   = $tmp_folder . '/db.sql';

        //Export the database and output the status to the page
        $command = 'mysqldump --opt -u ' . $mysqlUserName . ' -p' . $mysqlPassword . ' ' . $mysqlDatabaseName . ' > ' . $mysqlExportPath;
        exec($command, $output = [], $worked);
    }


    private function copyMedia($source, $destination)
    {
        exec("cp -r $source $destination/files/");
    }


    private function makeZipFile($tmp_folder)
    {
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($tmp_folder . '/files.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmp_folder), RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath     = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tmp_folder) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }


    /**
     * Updated mediafile by id
     *
     * @param $id
     *
     * @return array
     */
    public function actionUpdate($id)
    {
        /** @var Mediafile $model */
        $model   = Mediafile::findOne($id);
        $message = Yii::t('backend', 'Changes not saved.');

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $message = Yii::t('backend', 'Changes saved!');
        }

        Yii::$app->session->setFlash('mediafileUpdateResult', $message);

        return $this->renderPartial('info', [
            'model'       => $model,
            'strictThumb' => null,
        ]);
    }


    /**
     * Delete model with files
     *
     * @param $id
     *
     * @return array
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $routes                     = $this->module->routes;

        /** @var Mediafile $model */
        $model = Mediafile::findOne($id);

        if ($model->isImage())
        {
            $model->deleteThumbs($routes);
        }

        $model->deleteFile($routes);
        $model->delete();

        return [ 'success' => 'true' ];
    }


    /**
     * Resize all thumbnails
     */
    public function actionResize()
    {
        /** @var Mediafile[] $models */
        $models = Mediafile::findByTypes(Mediafile::$imageFileTypes);
        $routes = $this->module->routes;

        foreach ($models as $model)
        {
            if ($model->isImage())
            {
                $model->deleteThumbs($routes);
                $model->createThumbs($routes, $this->module->thumbs);
            }
        }

        Yii::$app->session->setFlash('successResize');
        $this->redirect(Url::to([ 'default/settings' ]));
    }


    /** Render model info
     *
     * @param int    $id
     * @param string $strictThumb only this thumb will be selected
     *
     * @return string
     */
    public function actionInfo($id, $strictThumb = null)
    {
        $model = Mediafile::findOne($id);

        return $this->renderPartial('info', [
            'model'       => $model,
            'strictThumb' => $strictThumb,
        ]);
    }


    /**
     * Provides upload file
     * @return mixed
     */
    public function actionUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model  = new Mediafile();
        $routes = $this->module->routes;
        $rename = $this->module->rename;
        $model->saveUploadedFile($routes, $rename);
        $bundle = FilemanagerAsset::register($this->view);

        if ($model->isImage())
        {
            $model->createThumbs($routes, $this->module->thumbs);
        }

        $response[ 'files' ][] = [
            'url'          => $model->url,
            'thumbnailUrl' => $bundle->baseUrl . $model->getDefaultThumbUrl($bundle->baseUrl),
            'name'         => $model->filename,
            'type'         => $model->type,
            'size'         => $model->file->size,
            'deleteUrl'    => Url::to([ 'default/delete', 'id' => $model->id ]),
            'deleteType'   => 'POST',
        ];

        return $response;
    }
}
