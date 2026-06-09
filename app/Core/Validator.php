<?php
namespace NanoPHP\Core;

class Validator {
    private array $data;
    private array $rules;
    private array $errors = [];
    private array $validated = [];

    private array $aliases = [];

    private const PATTERNS = [
        'email' => '/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
        'url' => '/^https?:\/\/.+/',
        'alpha' => '/^[a-zA-Z]+$/',
        'alphanumeric' => '/^[a-zA-Z0-9]+$/',
        'numeric' => '/^[0-9]+(?:\.[0-9]+)?$/',
    ];

    public function __construct(array $data, array $rules) {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function passes(): bool {
        $this->errors = [];
        $this->validated = [];

        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                $this->validateRule($field, $value, $rule);
            }

            if (!isset($this->errors[$field])) {
                $this->validated[$field] = $value;
            }
        }

        return empty($this->errors);
    }

    public function fails(): bool {
        return !$this->passes();
    }

    public function errors(): array {
        return $this->errors;
    }

    public function error(string $field): ?string {
        return $this->errors[$field] ?? null;
    }

    public function getData(): array {
        return $this->validated;
    }

    public function setAlias(string $field, string $alias): void {
        $this->aliases[$field] = $alias;
    }

    private function validateRule(string $field, mixed $value, string $rule): void {
        if (str_contains($rule, ':')) {
            [$name, $param] = explode(':', $rule, 2);
        } else {
            $name = $rule;
            $param = null;
        }

        $label = $this->aliases[$field] ?? $field;

        match ($name) {
            'required' => $this->checkRequired($field, $value, $label),
            'email' => $this->checkPattern($field, $value, $label, 'email'),
            'url' => $this->checkPattern($field, $value, $label, 'url'),
            'alpha' => $this->checkPattern($field, $value, $label, 'alpha'),
            'alphanumeric' => $this->checkPattern($field, $value, $label, 'alphanumeric'),
            'numeric' => $this->checkPattern($field, $value, $label, 'numeric'),
            'min' => $this->checkMin($field, $value, $label, $param),
            'max' => $this->checkMax($field, $value, $label, $param),
            'matches' => $this->checkMatches($field, $value, $label, $param),
            'regex' => $this->checkRegex($field, $value, $label, $param),
            default => null,
        };
    }

    private function addError(string $field, string $message): void {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    private function checkRequired(string $field, mixed $value, string $label): void {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            $this->addError($field, "$label is required.");
        }
    }

    private function checkPattern(string $field, mixed $value, string $label, string $pattern): void {
        if ($value === null || $value === '') return;
        if (!preg_match(self::PATTERNS[$pattern], (string) $value)) {
            $this->addError($field, "$label is not valid.");
        }
    }

    private function checkMin(string $field, mixed $value, string $label, ?string $param): void {
        if ($value === null || $value === '' || $param === null) return;
        $min = (int) $param;
        if (is_string($value) && mb_strlen($value) < $min) {
            $this->addError($field, "$label must be at least $min characters.");
        }
        if (is_numeric($value) && (float) $value < $min) {
            $this->addError($field, "$label must be at least $min.");
        }
    }

    private function checkMax(string $field, mixed $value, string $label, ?string $param): void {
        if ($value === null || $value === '' || $param === null) return;
        $max = (int) $param;
        if (is_string($value) && mb_strlen($value) > $max) {
            $this->addError($field, "$label must not exceed $max characters.");
        }
        if (is_numeric($value) && (float) $value > $max) {
            $this->addError($field, "$label must not exceed $max.");
        }
    }

    private function checkMatches(string $field, mixed $value, string $label, ?string $param): void {
        if ($value === null || $value === '' || $param === null) return;
        $other = $this->data[$param] ?? null;
        if ((string) $value !== (string) $other) {
            $labelOther = $this->aliases[$param] ?? $param;
            $this->addError($field, "$label does not match $labelOther.");
        }
    }

    private function checkRegex(string $field, mixed $value, string $label, ?string $param): void {
        if ($value === null || $value === '' || $param === null) return;
        if (!preg_match($param, (string) $value)) {
            $this->addError($field, "$label is not valid.");
        }
    }
}
