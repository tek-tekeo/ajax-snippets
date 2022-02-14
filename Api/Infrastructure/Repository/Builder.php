<?php
namespace AjaxSnippets\Infrastructure\Repository;

use AjaxSneppets\Domain\Models\BaseModel;

class Builder
{
    private $tableName = '';
    private $where = [];
    private $select = [];
    private $model = null;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    public function where(...$args): Builder
    {
        $argsCount = count($args);

        switch ($argsCount) {
            case 2:
                $key = $args[0];
                $colType = $this->model->getColumnType($key);
                $value = $colType === 'string' ? "'{$args[1]}'" : $args[1];
                $this->where[] = "{$key} = {$value}";
                break;
            case 3:
                $key = $args[0];
                $colType = $this->model->getColumnType($key);
                $value = $colType === 'string' ? "'{$args[2]}'" : $args[2];
                $this->where[] = "{$key} {$args[1]} {$value}";
                break;
            default:
                break;
        }

        return $this;
    }

    private function getSelectSql(): string
    {
        global $wpdb;

        $tableName = $this->model->getTableName();
        $sql = "SELECT * FROM {$tableName} ";
        if (count($this->where) > 0) {
            $where = join(' AND ', $this->where);
            $sql .= "WHERE " . $where;
        }

        return $sql;
    }

    private function dataToModel(string $modelName, object $row)
    {
        $model = new $modelName();

        foreach ($model->getColumns() as $column) {
            $model->$column = $row->$column;
        }


        return $model;
    }

    private function dataArrayToModels(array $rows, string $modelName): array
    {
        $models = [];

        foreach ($rows as $row) {
            $models[] = $this->dataToModel($modelName, $row);
        }

        return $models;
    }

    public function first()
    {
        global $wpdb;

        $sql = $this->getSelectSql();

        $rows = $wpdb->get_results($sql);

        if (empty($rows)) {
            return null;
        }

        $modelName = get_class($this->model);
        $model = $this->dataToModel($modelName, $rows[0]);

        return $model;
    }

    public function get(): array
    {
        global $wpdb;

        $sql = $this->getSelectSql();

        $rows = $wpdb->get_results($sql);
        if (empty($rows)) {
            return [];
        }

        $modelName = get_class($this->model);
        $models = $this->dataArrayToModels($rows, $modelName);

        return $models;
    }
}