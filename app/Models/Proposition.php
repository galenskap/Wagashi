<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Proposition extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currentpropositions';


}
