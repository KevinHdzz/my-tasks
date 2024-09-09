<?php

/**
 * FnArray = Functional Array
 */
class FnArray {
    private array $arr;

    public function __construct(...$values) {
        $this->arr = [...$values];
    }

    public function forEach(Closure $fn): void {
        for ($i=0; $i < count($this->arr); $i++) { 
            $fn($this->arr[$i]);
        }
    }

    public function find(Closure $fn): mixed|null {
        for ($i=0; $i < count($this->arr); $i++) { 
            if ($fn($this->arr[$i])) {
                return $this->arr[$i];
            }
        }

        return null;
    }

    public function filter(Closure $fn): array {
        $filtered = array();

        for ($i=0; $i < count($this->arr); $i++) { 
            if ($fn($this->arr[$i])) {
                $filtered[] = $this->arr[$i];
            }

            return $filtered;
        }
    }
    
    public function map(Closure $fn): array {
        $result = array();

        for ($i=0; $i < count($this->arr); $i++) { 
            $result[] = $fn($this->arr[$i]);
        }

        return $result;
    }

    public function includes(mixed $searchItem): bool {
        return in_array($searchItem, $this->arr);
    }
}

class MyArray {
    /**
     * @param array $arr The array to iterate.
     * @param Closure $fn
     */
    public static function forEach(array $arr, Closure $fn): void {
        for ($i = 0; $i, $i < count($arr); $i++) {
            $fn($arr[$i], $i);
        }
    }

    public static function filter(array $arr, Closure $fn): array {
        $filteredArr = [];
        
        for ($i = 0; $i < count($arr); $i++) {
            if ($fn($arr[$i], $i)) {
                $filteredArr[] = $arr[$i];
            }
        }

        return $filteredArr;
    }

    public static function map(array $arr, Closure $fn) : array {
        $mapped = array(); 

        for ($i = 0; $i < count($arr); $i++) {
            array_push($fn($arr[$i]));
        }

        return $mapped;
    }
}

$names = ['Kavin', 'Danna', 'Lili'];

MyArray::forEach(
    $names,
    fn (string $name, int $index) => print_r("name: $name index: $index \n")
);

$oddNums = MyArray::filter(
    [1, 2, 3, 4, 5, 6, 7, 8, 9],
    fn (int $num, int $index) => ($num % 2 == 1)
);

echo PHP_EOL . "Filter:" . PHP_EOL;
var_dump($oddNums);

echo PHP_EOL . "FnArray:" . PHP_EOL;
$fnArray = new FnArray(
    ["name" => "Kavin", "lastName" => "Garcia", "phone" => "555-6530"],
    ["name" => "Jhon", "lastName" => "Doe", "phone" => "555-7892"],
    ["name" => "Lili", "lastName" => "Garcia", "phone" => "555-2309"],
);

$filtered = $fnArray->filter(fn(array $item, int $index, array $arr)
                            => in_array(random_int(0, 10.00) * 10, $arr));
