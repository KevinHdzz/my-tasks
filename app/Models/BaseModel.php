<?php

namespace Kevinhdzz\MyTasks\Models;

use DateTime;
use Kevinhdzz\MyTasks\Database\DB;
use Kevinhdzz\MyTasks\Enums\ConversionFormats;

class BaseModel {
    /**
     * Database connection instance.
     */
    protected static DB $db;

    /**
     * The table associated with the model.
     */
    protected string $table;

    /**
     * Columns of the table associated with the model.
     */
    protected array $columns;

    /**
     * The primary key for the model.
     */
    protected string $primaryKey = "id";
    
    /**
     * Non-updatable columns.
     */
    protected array $immutableColumns;

    /**
     * The model's unique identifier.
     */
    public ?int $id = null;

    /**
     * Automatically insert 'created_at' and 'updated_at' columns.
     */
    protected bool $insertTimestamps = true;

    /**
     * Model timestamps. Initialized only if `$insertTimestamps` is true.
     * 
     * @var array<string, DateTime|null> $timestamps
     */
    public array $timestamps;

    /**
     * Initialize model.
     */
    public function __construct()
    {
        $this->immutableColumns = [$this->primaryKey];

        if ($this->insertTimestamps) {
            $this->timestamps = [
                "created_at" => null,
                "updated_at" => null,
            ];

            if (!in_array($this->primaryKey, $this->columns)) $this->columns[] = $this->primaryKey;
            if (!in_array("created_at", $this->columns)) $this->columns[] = "created_at";
            if (!in_array("updated_at", $this->columns)) $this->columns[] = "updated_at";

            $this->immutableColumns[] = "created_at";
        }
    }

    /**
     * Sets the database connection instance.
     * 
     * @param DB $db
     */
    public static function setDb(DB $db): void
    {
        self::$db = $db;
    }

    /**
     * Sets model properties from a database row.
     * 
     * @param array<string, mixed> $row
     * @return $this
     */
    public function setProps(array $row): static
    {
        $formattedRow = static::formatFields($row, ConversionFormats::COLS_TO_PROPS);

        if ($this->insertTimestamps) {
            $this->timestamps["created_at"] = $formattedRow["created_at"];
            $this->timestamps["updated_at"] = $formattedRow["updated_at"];
        }
        
        foreach ($formattedRow as $col => $value) {
            if (property_exists($this, $col)) $this->$col = $value;
        }

        return $this;
    }

    /**
     * Maps current model property values to their corresponding columns.
     * 
     * @return array  An associative array with column names as keys and the current property values as values.
     */
    public function mapPropertiesToColumns(): array
    {
        return array_combine(
            $this->columns,
            array_map(function (string $col) {
                if ($this->insertTimestamps && key_exists($col, $this->timestamps)) {
                    return $this->timestamps[$col];
                }
                
                return $this->$col;
            }, $this->columns)
        );
    }

    /**
     * Defines formatting rules for converting between model properties and database columns.
     * 
     * @param ConversionFormats $format  The conversion format specifying the direction of transformation
     *                                   (e.g., columns to properties or properties to columns).
     * 
     * @return array<string, callable(mixed): mixed>  An associative array with column names as keys and formatting functions as values.
     */
    protected static function formatPropsAndCols(ConversionFormats $format): array
    {
        return match ($format) {
            ConversionFormats::COLS_TO_PROPS => [
                "created_at" => fn (string $date): DateTime => new DateTime($date),
                "updated_at" => fn (string $date): DateTime => new DateTime($date),
            ],
            ConversionFormats::PROPS_TO_COLS => [
                "created_at" => fn (DateTime $date): string => $date->format("Y-m-d H:i:s"),
                "updated_at" => fn (DateTime $date): string => $date->format("Y-m-d H:i:s"),
            ],
        };
    }

    /**
     * Applies formatting rules to a set of fields based on the specified conversion format.
     * Each field is transformed according to its associated formatting function if available.
     * 
     * @param array<string, mixed> $fields  An associative array where keys are column names and values are the data to be formatted.
     * @param ConversionFormats $format  The conversion format specifying the direction of the transformation
     *                                   (e.g., columns to properties or properties to columns).
     * 
     * @return array<string, mixed>  An associative array with formatted values based on the specified conversion format.
     */
    public static function formatFields(array $fields, ConversionFormats $format): array
    {
        return array_combine(
            array_keys($fields),
            array_map(
                function (string $col) use ($fields, $format): mixed {
                    $value = $fields[$col];
                    return isset(static::formatPropsAndCols($format)[$col]) ? static::formatPropsAndCols($format)[$col]($value) : $value;
                },
                array_keys($fields)
            )
        );
    }

    /**
     * Saves the model's current state to the database.
     * Creates a new record if the model's `$id` is null, otherwise, updates the existing record.
     * 
     * @return $this
     */
    public function save(): static
    {
        return is_null($this->id) ? $this->create() : $this->update();
    }

    /**
     * Saves the current model in the database.
     * 
     * @return $this
     */
    public function create(): static
    {
        $columnValues = $this->mapPropertiesToColumns();
        // Remove primary key, so that the database inserts it.
        unset($columnValues[$this->primaryKey]);
        
        if ($this->insertTimestamps) {
            $columnValues["updated_at"] = $columnValues["created_at"] = $this->timestamps["updated_at"] = $this->timestamps["created_at"] = new DateTime('now');
        }

        $formattedColVals = static::formatFields($columnValues, ConversionFormats::PROPS_TO_COLS);

        $columnsStr = implode(", ", array_keys($formattedColVals));
        $placeholders = implode(", ", array_fill(0, count($formattedColVals), "?"));
        $query = "INSERT INTO $this->table ($columnsStr) VALUES ($placeholders)";

        self::$db->statement($query, array_values($formattedColVals));
        
        $this->id = self::$db->pdo->lastInsertId();

        return $this;
    }

    /**
     * Updates the model in the database with its current state.
     * 
     * @return $this
     */
    private function update(): static
    {
        $formattedColVals = $this->formatFields($this->mapPropertiesToColumns(), ConversionFormats::PROPS_TO_COLS);
        
        foreach ($this->immutableColumns as $col) {
            unset($formattedColVals[$col]);  
        }
        
        if ($this->insertTimestamps) {
            $this->timestamps["updated_at"] = new DateTime("now");
            $formattedColVals["updated_at"] = (
                static::formatPropsAndCols(ConversionFormats::PROPS_TO_COLS)["updated_at"]($this->timestamps["updated_at"])
            );
        }

        $sets = array_map(fn (string $col) => "$col = ?", array_keys($formattedColVals));
        $setsStr = implode(", ", $sets);
        $query = "UPDATE $this->table SET $setsStr WHERE id = ?";

        self::$db->statement($query, array_values($formattedColVals + [$this->id]));
        
        return $this;
    }

    /**
     * Get the models where `$column` = `$value`.
     * 
     * @param string $column
     * @param mixed $value
     * @return static[]
     */
    public static function where(string $column, mixed $value): array
    {
        $model = new static;
        $rows = self::$db->statement("SELECT * FROM $model->table WHERE $column = ?", [$value]);

        if (count($rows) == 0) {
            return [];
        }

        $models = [$model->setProps($rows[0])];

        for ($i = 1; $i < count($rows); $i++) { 
            $models[] = (new static())->setProps($rows[$i]);
        }
        
        return $models;
    }

    /**
     * Retrieves all models from the database.
     * 
     * @return static[]
     */
    public static function all(): array
    {
        $model = new static();
        $rows = self::$db->statement("SELECT * FROM $model->table");
        if (count($rows) == 0) {
            return [];
        }
        
        $models = [$model->setProps($rows[0])];

        for ($i = 1; $i < count($rows); $i++) {
            $models[] = (new static())->setProps($rows[$i]);
        }

        return $models;
    }

    /**
     * Finds a model with the given `$id`.
     * 
     * @param int $id
     * @return static|null  The model instance if found, otherwise null.
     */
    public static function find(int $id): ?static
    {
        $model = new static();
        $rows = self::$db->statement("SELECT * FROM $model->table WHERE id = ?", [$id]);

        if (count($rows) == 0) {
            return null;
        }
        
        return $model->setProps($rows[0]);
    }
}
