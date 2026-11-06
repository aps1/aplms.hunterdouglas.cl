<?php

namespace App;

use App\Traits\Localizable;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    use Localizable;

    /**
     * @var array
     */
    public $guarded = [];

    /**
     * Belongs to lead
     *
     * @return void
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
