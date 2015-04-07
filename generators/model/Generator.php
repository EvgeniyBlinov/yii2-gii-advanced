<?php

namespace generators\model;

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
     * @var string
     **/
    public $ns        = 'common\models';

    /**
     * @var string
     **/
    public $baseClass = '\common\models\Base';

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
        $files     = [];
        $relations = $this->generateRelations();
        $db        = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            $className   = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $params      = [
                'tableName'   => $tableName,
                'className'   => $className,
                'tableSchema' => $tableSchema,
                'labels'      => $this->generateLabels($tableSchema),
                'rules'       => $this->generateRules($tableSchema),
                'relations'   => isset($relations[$className]) ? $relations[$className] : [],
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . lcfirst($className) . '/' . $className . 'Base.php',
                $this->render('modelBase.php', $params)
            );
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . lcfirst($className) . '/' . $className . '.php',
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
}
