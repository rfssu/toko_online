<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AutoFill
{
    protected $model;
    protected $params;
    protected $options;

    /**
     * Default options
     */
    protected $defaults = [
        'except' => ['_token', '_method'],
        'password_fields' => ['password'],
        'hash_password' => true,
        'fillable_only' => true,
    ];

    /**
     * Constructor
     */
    public function __construct(Model $model, array $params, array $options = [])
    {
        $this->model = $model;
        $this->params = $params;
        $this->options = array_merge($this->defaults, $options);
    }

    /**
     * Static method untuk penggunaan yang lebih mudah
     */
    public static function fill(Model $model, array $params, array $options = [])
    {
        $instance = new static($model, $params, $options);
        return $instance->process();
    }

    /**
     * Process the filling
     */
    public function process()
    {
        $this->removeExcludedFields();
        $this->handlePasswordFields();
        $this->filterFillableOnly();

        $this->model->fill($this->params);

        return $this->model;
    }

    /**
     * Remove excluded fields
     */
    protected function removeExcludedFields()
    {
        foreach ($this->options['except'] as $field) {
            unset($this->params[$field]);
        }
    }

    /**
     * Handle password fields
     */
    protected function handlePasswordFields()
    {
        foreach ($this->options['password_fields'] as $field) {
            // Remove confirmation field
            unset($this->params[$field . '_confirmation']);

            if (isset($this->params[$field])) {
                // If password is empty or null, keep existing password
                if (empty($this->params[$field])) {
                    $this->params[$field] = $this->model->{$field};
                }
                // Hash password if not already hashed and hash_password is enabled
                elseif ($this->options['hash_password'] && !$this->isHashed($this->params[$field])) {
                    $this->params[$field] = Hash::make($this->params[$field]);
                }
            } else {
                // Keep existing password if not provided
                if ($this->model->exists) {
                    $this->params[$field] = $this->model->{$field};
                }
            }
        }
    }

    /**
     * Filter only fillable attributes
     */
    protected function filterFillableOnly()
    {
        if (!$this->options['fillable_only']) {
            return;
        }

        $fillable = $this->model->getFillable();

        if (!empty($fillable)) {
            $this->params = array_intersect_key($this->params, array_flip($fillable));
        }
    }

    /**
     * Check if string is hashed
     */
    protected function isHashed($value)
    {
        return is_string($value) && (
            str_starts_with($value, '$2y$') ||
            str_starts_with($value, '$2a$') ||
            str_starts_with($value, '$2b$')
        );
    }

    /**
     * Add custom processor
     */
    public function addProcessor(callable $callback)
    {
        $this->params = $callback($this->params, $this->model);
        return $this;
    }

    /**
     * Get processed params
     */
    public function getParams()
    {
        return $this->params;
    }
}
