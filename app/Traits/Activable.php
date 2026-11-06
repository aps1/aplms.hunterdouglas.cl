<?php

namespace App\Traits;

/**
 * Para los modelos que se activan e inactivan
 */
trait Activable
{
    /**
     * activa el recurso
     *
     * @return void
     */
    public function activate()
    {
        return $this->update(['is_active' => 1]);
    }

    /**
     * inactiva el recurso
     *
     * @return void
     */
    public function inactivate()
    {
        return $this->update(['is_active' => 0]);
    }

    /**
     * Scope a query to only include active resources.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
