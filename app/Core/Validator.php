<?php
namespace App\Core;

class Validator
{
    private array $errors = [];

    public static function validate(array $data, array $rules): array
    {
        $v = new self();
        foreach ($rules as $field => $ruleStr) {
            $fieldRules = explode('|', $ruleStr);
            foreach ($fieldRules as $rule) {
                $v->applyRule($field, $data[$field] ?? null, $rule, $data);
            }
        }
        if (!empty($v->errors)) {
            Session::set('validation_errors', $v->errors);
            Session::set('old_input', $data);
            $ref = $_SERVER['HTTP_REFERER'] ?? '/';
            redirect($ref);
            exit;
        }
        return $data;
    }

    public static function make(array $data, array $rules): self
    {
        $v = new self();
        foreach ($rules as $field => $ruleStr) {
            $fieldRules = explode('|', $ruleStr);
            foreach ($fieldRules as $rule) {
                $v->applyRule($field, $data[$field] ?? null, $rule, $data);
            }
        }
        return $v;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function applyRule(string $field, mixed $value, string $rule, array $data): void
    {
        $label = ucfirst(str_replace('_', ' ', $field));

        if (str_starts_with($rule, 'required')) {
            if ($value === null || $value === '') {
                $this->errors[$field][] = "Le champ {$label} est obligatoire.";
            }
            return;
        }

        if ($value === null || $value === '') {
            return; // Champ optionnel vide : on ne valide pas le reste
        }

        if ($rule === 'email') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field][] = "Le champ {$label} doit être un email valide.";
            }
            return;
        }

        if (str_starts_with($rule, 'min:')) {
            $min = (int) substr($rule, 4);
            if (strlen((string) $value) < $min) {
                $this->errors[$field][] = "Le champ {$label} doit contenir au moins {$min} caractères.";
            }
            return;
        }

        if (str_starts_with($rule, 'max:')) {
            $max = (int) substr($rule, 4);
            if (strlen((string) $value) > $max) {
                $this->errors[$field][] = "Le champ {$label} ne doit pas dépasser {$max} caractères.";
            }
            return;
        }

        if ($rule === 'numeric') {
            if (!is_numeric($value)) {
                $this->errors[$field][] = "Le champ {$label} doit être un nombre.";
            }
            return;
        }

        if ($rule === 'integer') {
            if (!ctype_digit((string) $value)) {
                $this->errors[$field][] = "Le champ {$label} doit être un entier.";
            }
            return;
        }

        if ($rule === 'date') {
            if (!\DateTime::createFromFormat('Y-m-d', $value)) {
                $this->errors[$field][] = "Le champ {$label} doit être une date valide (AAAA-MM-JJ).";
            }
            return;
        }

        if (str_starts_with($rule, 'in:')) {
            $allowed = explode(',', substr($rule, 3));
            if (!in_array($value, $allowed, true)) {
                $this->errors[$field][] = "La valeur du champ {$label} est invalide.";
            }
            return;
        }

        if (str_starts_with($rule, 'unique:')) {
            // unique:table,column
            $parts  = explode(',', substr($rule, 7));
            $table  = $parts[0];
            $column = $parts[1] ?? $field;
            $db     = Database::getInstance();
            $stmt   = $db->prepare("SELECT COUNT(*) FROM `$table` WHERE `$column` = ? AND deleted_at IS NULL");
            $stmt->execute([$value]);
            if ($stmt->fetchColumn() > 0) {
                $this->errors[$field][] = "La valeur du champ {$label} est déjà utilisée.";
            }
            return;
        }

        if ($rule === 'confirmed') {
            if ($value !== ($data["{$field}_confirmation"] ?? null)) {
                $this->errors[$field][] = "La confirmation du champ {$label} ne correspond pas.";
            }
            return;
        }
    }
}
