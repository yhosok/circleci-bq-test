<?php
namespace App\Models\BigQuery;


use Google\Cloud\BigQuery\Numeric;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Model
 *
 * @package App\Models\BigQuery
 */
class Model implements Arrayable
{
    protected $fields = [];

    protected $data = [];

    public function __construct($data = null)
    {
        if ($data) {
            $this->data = collect($data)
                ->filter(function ($value, $key) {
                    return in_array($key, $this->fields, true);
                })
                ->all();
        }
    }

    public function __set($name, $value)
    {
        if (in_array($name, $this->fields, true)) {
            $this->data[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            $value = $this->data[$name];
            if ($value instanceof Numeric) {
                $value = $value->get();
            }

            return $value;
        } else {
            return null;
        }
    }

    public function toArray()
    {
        return $this->data;
    }
}
