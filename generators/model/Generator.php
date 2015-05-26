<?php

namespace cent\gii\generators\model;

use Yii;
use yii\gii\CodeFile;

/**
 * Generator
 *
 * @package default
 * @author Evgeniy Blinov <evgeniy_blinov@mail.ru>
**/
class Generator extends \yii\gii\generators\model\Generator
{
    /**
     * @var string of models path
     **/
    public $ns         = 'common\models';

    /**
     * @var string of Base models path
     **/
    public $nsBase     = 'common\models';

    /**
     * @var string
     **/
    public $baseClass  = '\common\models\Base';

    /**
     * @var boolean
     **/
    public $enableI18N  = true;

    /**
     * @var boolean
     **/
    public $useSubFolder = false;

    /**
     * @return array of required templates
     */
    public function requiredTemplates()
    {
        return ['model.php','modelBase.php'];
    }

    /**
     * Generate
     *
     * @return array
     */
    public function generate()
    {
        $files        = [];
        $relations    = $this->generateRelations();
        $db           = $this->getDbConnection();
        $this->nsBase = $this->nsBase ?: $this->ns;
        foreach ($this->getTableNames() as $tableName) {
            $className   = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $subFolder = $this->useSubFolder ? '/' . lcfirst($className) : '';
            $params      = [
                'tableName'      => $tableName,
                'className'      => $className,
                'tableSchema'    => $tableSchema,
                'labels'         => $this->generateLabels($tableSchema),
                'rules'          => $this->generateRules($tableSchema),
                'relations'      => isset($relations[$className]) ? $relations[$className] : [],
                'namespaceBase'  => $this->nsBase . ($this->useSubFolder ? '\\' . lcfirst($className) : ''),
                'namespaceModel' => $this->ns . ($this->useSubFolder ? '\\' . lcfirst($className) : ''),
            ];
            $files[]   = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->nsBase)) . $subFolder . '/' . $className . 'Base.php',
                $this->render('modelBase.php', $params)
            );
            $files[]   = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . $subFolder . '/' . $className . '.php',
                $this->render('model.php', $params)
            );
        }

        return $files;
    }

    /**
     * Generates validation rules for the specified table.
     * @param \yii\db\TableSchema $table the table schema
     * @return array the generated validation rules
     */
    public function generateRules($table)
    {
        if (isset($table->columns) && is_array($table->columns)) {
            $excludeFromRequiredRule = ['created_date', 'updated_date'];
            foreach ($excludeFromRequiredRule as $columnName) {
                if (array_key_exists($columnName, $table->columns)) {
                    $table->columns[$columnName]->allowNull = true;
                }
            }
        }
        return parent::generateRules($table);
    }

    /**
     * @return array of rules
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['nsBase'], 'filter', 'filter' => 'trim'],
            [['nsBase'], 'filter', 'filter' => function($value) { return trim($value, '\\'); }],

            [['nsBase'], 'required'],
            [['nsBase'], 'match', 'pattern' => '/^[\w\\\\]+$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['nsBase'], 'validateNamespace'],
            [['useSubFolder'], 'boolean'],
        ]);
    }

    /**
     * @array of attribute labels
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'nsBase' => 'Namespace of Base models',
            'useSubFolder' => 'Use sub folder',
        ]);
    }

    /**
     * @return array of hints
     **/
    public function hints()
    {
        return array_merge(parent::hints(), [
            'nsBase' => 'This is the namespace of the ActiveRecord class to be generated, e.g., <code>app\models</code>',
            'useSubFolder' => 'This indicates whether to add subfolder to generated models',
        ]);
    }
}
