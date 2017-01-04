<?php

namespace mobilejazz\yii2\cms\backend\modules\filemanager\models;

use mobilejazz\yii2\cms\backend\modules\filemanager\Module;
use Imagine\Image\ImageInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "filemanager_mediafile".
 *
 * @property integer $id
 * @property string  $filename
 * @property string  $type
 * @property string  $url
 * @property string  $alt
 * @property integer $size
 * @property string  $description
 * @property string  $thumbs
 * @property integer $created_at
 * @property integer $updated_at
 */
class Mediafile extends ActiveRecord
{

    public $file;

    const IMAGE_GIF = 'image/gif';
    const IMAGE_JPEG = 'image/jpeg';
    const IMAGE_PNG = 'image/png';
    const APPLICATION_PDF = 'application/pdf';
    const AUDIO_MPEG = 'audio/mpeg';
    const AUDIO_MPEG3 = 'audio/mpeg3';
    const AUDIO_X_MPEG = 'audio/x-mpeg';
    const AUDIO_X_MPEG_3 = 'audio/x-mpeg-3';

    public static $imageFileTypes = [
        self::IMAGE_GIF,
        self::IMAGE_GIF,
        self::IMAGE_JPEG,
        self::IMAGE_PNG,
        self::APPLICATION_PDF,
        self::AUDIO_MPEG,
        self::AUDIO_MPEG3,
        self::AUDIO_X_MPEG,
        self::AUDIO_X_MPEG_3,
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filemanager_mediafile';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'filename', 'type', 'url', 'size' ], 'required' ],
            [ [ 'url', 'alt', 'description', 'thumbs' ], 'string' ],
            [ [ 'created_at', 'updated_at', 'size' ], 'integer' ],
            [ [ 'filename', 'type' ], 'string', 'max' => 255 ],
            [ [ 'file' ], 'file' ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('backend', 'ID'),
            'filename'    => Yii::t('backend', 'filename'),
            'type'        => Yii::t('backend', 'Type'),
            'url'         => Yii::t('backend', 'Url'),
            'alt'         => Yii::t('backend', 'Alt attribute'),
            'size'        => Yii::t('backend', 'Size'),
            'description' => Yii::t('backend', 'Description'),
            'thumbs'      => Yii::t('backend', 'Thumbnails'),
            'created_at'  => Yii::t('backend', 'Created'),
            'updated_at'  => Yii::t('backend', 'Updated'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ],
        ];
    }


    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /**
     * Save just uploaded file
     *
     * @param array $routes routes from module settings
     * @param bool  $rename
     *
     * @return bool
     */
    public function saveUploadedFile(array $routes, $rename = false)
    {
        $year         = date('Y', time());
        $month        = date('m', time());
        $structure    = "$routes[baseUrl]/$routes[uploadPath]/$year/$month";
        $basePath     = Yii::getAlias($routes[ 'basePath' ]);
        $absolutePath = "$basePath/$structure";

        // create actual directory structure "yyyy/mm"
        if (!file_exists($absolutePath))
        {
            mkdir($absolutePath, 0777, true);
        }

        // get file instance
        $this->file = UploadedFile::getInstance($this, 'file');
        //if a file with the same name already exist append a number
        $counter = 0;
        do
        {
            if ($counter == 0)
            {
                $filename = Inflector::slug($this->file->baseName) . '.' . $this->file->extension;
            }
            else
            {
                //if we don't want to rename we finish the call here
                if ($rename == false)
                {
                    return false;
                }
                $filename = Inflector::slug($this->file->baseName) . $counter . '.' . $this->file->extension;
            }
            $url = "$structure/$filename";
            $counter++;
        }
        while (self::findByUrl($url)); // checks for existing url in db

        // save original uploaded file
        $this->file->saveAs("$absolutePath/$filename");
        $this->filename = $filename;
        $this->type     = $this->file->type;
        $this->size     = $this->file->size;
        $this->url      = $url;

        return $this->save();
    }


    /**
     * Create thumbs for this image
     *
     * @param array $routes  see routes in module config
     * @param array $presets thumbs presets. See in module config
     *
     * @return bool
     */
    public function createThumbs(array $routes, array $presets)
    {
        $thumbs       = [ ];
        $basePath     = $basePath = Yii::getAlias($routes[ 'basePath' ]);
        $originalFile = pathinfo($this->url);
        $dirname      = $originalFile[ 'dirname' ];
        $filename     = $originalFile[ 'filename' ];
        $extension    = $originalFile[ 'extension' ];

        Image::$driver = [ Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK ];

        foreach ($presets as $alias => $preset)
        {
            $width  = $preset[ 'size' ][ 0 ];
            $height = $preset[ 'size' ][ 1 ];
            $mode   = (isset($preset[ 'mode' ]) ? $preset[ 'mode' ] : ImageInterface::THUMBNAIL_INSET);

            $thumbUrl = "$dirname/$filename-{$width}x{$height}.$extension";

            Image::thumbnail("$basePath/{$this->url}", $width, $height, $mode)
                 ->save("$basePath/$thumbUrl");

            $thumbs[ $alias ] = $thumbUrl;
        }

        $this->thumbs = serialize($thumbs);
        $this->detachBehavior('timestamp');

        // create default thumbnail
        $this->createDefaultThumb($routes);

        return $this->save();
    }


    /**
     * Create default thumbnail
     *
     * @param array $routes see routes in module config
     */
    public function createDefaultThumb(array $routes)
    {
        $originalFile = pathinfo($this->url);
        $dirname      = $originalFile[ 'dirname' ];
        $filename     = $originalFile[ 'filename' ];
        $extension    = $originalFile[ 'extension' ];

        Image::$driver = [ Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK ];

        $size     = Module::getDefaultThumbSize();
        $width    = $size[ 0 ];
        $height   = $size[ 1 ];
        $thumbUrl = "$dirname/$filename-{$width}x{$height}.$extension";
        $basePath = Yii::getAlias($routes[ 'basePath' ]);
        Image::thumbnail("$basePath/{$this->url}", $width, $height)
             ->save("$basePath/$thumbUrl");
    }


    /**
     * @param $baseUrl
     *
     * @return string default thumbnail for image
     */
    public function getDefaultThumbUrl($baseUrl = '')
    {
        if ($this->isImage())
        {
            $size         = Module::getDefaultThumbSize();
            $originalFile = pathinfo($this->url);
            $dirname      = $originalFile[ 'dirname' ];
            $filename     = $originalFile[ 'filename' ];
            $extension    = $originalFile[ 'extension' ];
            $width        = $size[ 0 ];
            $height       = $size[ 1 ];

            return "$dirname/$filename-{$width}x{$height}.$extension";
        }

        return "/img/file.png";
    }


    /**
     * @return string default thumbnail for image
     * @internal param $baseUrl
     */
    public function getDefaultUploadThumbUrl()
    {
        $size         = Module::getDefaultThumbSize();
        $originalFile = pathinfo($this->url);
        $dirname      = $originalFile[ 'dirname' ];
        $filename     = $originalFile[ 'filename' ];
        $extension    = $originalFile[ 'extension' ];
        $width        = $size[ 0 ];
        $height       = $size[ 1 ];

        return Yii::getAlias('@web') . "$dirname/$filename-{$width}x{$height}.$extension";
    }


    /**
     * @return array thumbnails
     */
    public function getThumbs()
    {
        return unserialize($this->thumbs);
    }


    /**
     * @param string $alias thumb alias
     *
     * @return string thumb url
     */
    public function getThumbUrl($alias)
    {
        $thumbs = $this->getThumbs();

        if ($alias === 'original')
        {
            return $this->url;
        }

        return !empty($thumbs[ $alias ]) ? $thumbs[ $alias ] : '';
    }


    /**
     * Thumbnail image html tag
     *
     * @param string $alias   thumbnail alias
     * @param array  $options html options
     *
     * @return string Html image tag
     */
    public function getThumbImage($alias, $options = [ ])
    {
        $url = $this->getThumbUrl($alias);

        if (empty($url))
        {
            return '';
        }

        if (empty($options[ 'alt' ]))
        {
            $options[ 'alt' ] = $this->alt;
        }

        return Html::img($url, $options);
    }


    /**
     * @param Module $module
     *
     * @return array images list
     */
    public function getImagesList(Module $module)
    {
        $thumbs             = $this->getThumbs();
        $list               = [ ];
        $originalImageSize  = $this->getOriginalImageSize($module->routes);
        $list[ $this->url ] = Yii::t('backend', 'Original') . ' ' . $originalImageSize;

        foreach ($thumbs as $alias => $url)
        {
            $preset       = $module->thumbs[ $alias ];
            $list[ $url ] = $preset[ 'name' ] . ' ' . $preset[ 'size' ][ 0 ] . ' × ' . $preset[ 'size' ][ 1 ];
        }

        return $list;
    }


    /**
     * Delete thumbnails for current image
     *
     * @param array $routes see routes in module config
     */
    public function deleteThumbs(array $routes)
    {
        $basePath = Yii::getAlias($routes[ 'basePath' ]);

        foreach ($this->getThumbs() as $thumbUrl)
        {
            unlink("$basePath/$thumbUrl");
        }

        unlink("$basePath/{$this->getDefaultThumbUrl()}");
    }


    /**
     * Delete file
     *
     * @param array $routes see routes in module config
     *
     * @return bool
     */
    public function deleteFile(array $routes)
    {
        $basePath = Yii::getAlias($routes[ 'basePath' ]);

        return unlink("$basePath/{$this->url}");
    }


    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = self::find()
                     ->orderBy('created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }


    /**
     * @return int last changes timestamp
     */
    public function getLastChanges()
    {
        return !empty($this->updated_at) ? $this->updated_at : $this->created_at;
    }


    /**
     * This method wrap getimagesize() function
     *
     * @param array  $routes    see routes in module config
     * @param string $delimiter delimiter between width and height
     *
     * @return string image size like '1366x768'
     */
    public function getOriginalImageSize(array $routes, $delimiter = ' × ')
    {
        $imageSizes = $this->getOriginalImageSizes($routes);

        return "$imageSizes[0]$delimiter$imageSizes[1]";
    }


    /**
     * This method wrap getimagesize() function
     *
     * @param array $routes see routes in module config
     *
     * @return array
     */
    public function getOriginalImageSizes(array $routes)
    {
        $basePath = Yii::getAlias($routes[ 'basePath' ]);

        return getimagesize("$basePath/{$this->url}");
    }


    /**
     * @return string file size
     */
    public function getFileSize()
    {
        Yii::$app->formatter->sizeFormatBase = 1000;

        return Yii::$app->formatter->asShortSize($this->size, 0);
    }


    /**
     * Find model by url
     *
     * @param $url
     *
     * @return static
     */
    public static function findByUrl($url)
    {
        return self::findOne([ 'url' => $url ]);
    }


    /**
     * Search models by file types
     *
     * @param array $types file types
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findByTypes(array $types)
    {
        return self::find()
                   ->filterWhere([ 'in', 'type', $types ])
                   ->all();
    }


    public function isImage()
    {
        return $this->type == self::IMAGE_GIF || $this->type == self::IMAGE_JPEG || $this->type == self::IMAGE_PNG;
    }
}
