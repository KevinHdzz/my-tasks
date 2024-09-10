<?php

namespace Kevinhdzz\MyTasks\Models;

use DateTime;
use Kevinhdzz\MyTasks\Database\DB;
use Kevinhdzz\MyTasks\Enums\ConversionFormats;

class BaseModel {
    protected static DB $db;
    protected string $table;
    protected array $columns;
    protected string $primaryKey = 'id';
    /** Non-updatable columns. */
    protected array $immutableColumns;

    public ?int $id = null;

    /** Another way to handle timestamps */
    protected bool $insertTimestamps = true;

    /**
     * Model timestamps. Is only initialized if `$insertTimestamps` is true.
     * 
     * @var array<string, DateTime|null> $timestamps
     */
    public array $timestamps;

    public function __construct()
    {
        $this->immutableColumns = [$this->primaryKey];

        if ($this->insertTimestamps) {
            $this->timestamps = [
                'created_at' => null,
                'updated_at' => null,
            ];

            if (!in_array($this->primaryKey, $this->columns)) $this->columns[] = $this->primaryKey;
            if (!in_array('created_at', $this->columns)) $this->columns[] = 'created_at';
            if (!in_array('updated_at', $this->columns)) $this->columns[] = 'updated_at';

            $this->immutableColumns[] = 'created_at';
        }
    }

    public static function setDb(DB $db): void
    {
        self::$db = $db;
    }

    # Pending: Map columns with model properties values
    public function columnValues(): array
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

    public function setProps(array $row): static
    {
        $formattedRow = static::applyFormat($row, ConversionFormats::COLS_TO_PROPS);

        if ($this->insertTimestamps) {
            $this->timestamps['created_at'] = $formattedRow['created_at'];
            $this->timestamps['updated_at'] = $formattedRow['updated_at'];
        }
        
        foreach ($formattedRow as $col => $value) {
            if (property_exists($this, $col)) $this->$col = $value;
        }

        return $this;
    }

    protected static function formatPropsAndCols(ConversionFormats $format): array
    {
        return match ($format) {
            ConversionFormats::COLS_TO_PROPS => [
                'created_at' => fn (string $date): DateTime => new DateTime($date),
                'updated_at' => fn (string $date): DateTime => new DateTime($date),
            ],
            ConversionFormats::PROPS_TO_COLS => [
                'created_at' => fn (DateTime $date): string => $date->format('Y-m-d H:i:s'),
                'updated_at' => fn (DateTime $date): string => $date->format('Y-m-d H:i:s'),
            ],
        };
    }

    public static function applyFormat(array $columnValues, ConversionFormats $format): array
    {
        return array_combine(
            array_keys($columnValues),
            array_map(
                function (string $col) use ($columnValues, $format): mixed {
                    $value = $columnValues[$col];
                    return isset(static::formatPropsAndCols($format)[$col]) ? static::formatPropsAndCols($format)[$col]($value) : $value;
                },
                array_keys($columnValues)
            )
        );
    }

    public function save(): static
    {
        return is_null($this->id) ? $this->create() : $this->update();
    }

    public function create(): static
    {
        $columnValues = $this->columnValues();
        // Remove primary key, so that the database inserts it.
        unset($columnValues[$this->primaryKey]);
        
        if ($this->insertTimestamps) {
            // $this->timestamps['created_at'] = new DateTime('now');
            // $this->timestamps['updated_at'] = $this->timestamps['created_at'];
            // $columnValues['created_at'] = $this->timestamps['created_at'];
            // $columnValues['updated_at'] = $this->timestamps['updated_at'];
            $columnValues['updated_at'] = $columnValues['created_at'] = $this->timestamps['updated_at'] = $this->timestamps['created_at'] = new DateTime('now');
        }

        $formattedColVals = static::applyFormat($columnValues, ConversionFormats::PROPS_TO_COLS);

        $columnsStr = implode(", ", array_keys($formattedColVals));
        $placeholders = implode(", ", array_fill(0, count($formattedColVals), "?"));
        $query = "INSERT INTO $this->table ($columnsStr) VALUES ($placeholders)";

        self::$db->statement($query, array_values($formattedColVals));

        return $this;
    }

    private function update(): static
    {
        $formattedColVals = $this->applyFormat($this->columnValues(), ConversionFormats::PROPS_TO_COLS);
        
        foreach ($this->immutableColumns as $col) {
            unset($formattedColVals[$col]);  
        }
        
        if ($this->insertTimestamps) {
            $this->timestamps['updated_at'] = new DateTime('now');
            $formattedColVals['updated_at'] = (
                static::formatPropsAndCols(ConversionFormats::PROPS_TO_COLS)['updated_at']($this->timestamps['updated_at'])
            );
        }

        $sets = array_map(fn (string $col) => "$col = ?", array_keys($formattedColVals));
        $setsStr = implode(", ", $sets);
        $query = "UPDATE $this->table SET $setsStr WHERE id = ?";

        self::$db->statement($query, array_values($formattedColVals + [$this->id]));
        
        return $this;
    }

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
