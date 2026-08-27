<?php

namespace App\Traits;

trait SanitizesInput
{
    protected function sanitizeOnly(array $fields): void
    {
        $data = [];

        foreach ($fields as $field) {
            if ($this->has($field)) {
                $value = $this->$field;

                // If numeric, cast properly
                if (is_numeric($value)) {
                    $data[$field] = $value + 0; // auto-cast int/float
                    continue;
                }

                // Otherwise sanitize string
                $data[$field] = $this->sanitize($value);
            }
        }

        $this->merge($data);
    }

    protected function sanitizeAll(array $fields): void
    {
        $data = [];

        foreach ($fields as $field) {
            if (!$this->input($field)) {
                continue;
            }

            $value = $this->input($field);

            if (is_numeric($value)) {
                $data[$field] = $value + 0;
                continue;
            }

            $data[$field] = is_string($value)
                ? trim(strip_tags($value))
                : $value;
        }

        $this->merge($data);
    }
    protected function sanitize($value)
    {
        return is_string($value)
            ? trim(strip_tags($value))
            : $value;
    }
}
