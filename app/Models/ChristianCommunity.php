<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChristianCommunity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'christiancommunity';
    use HasFactory;

    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'middlename',
        'birthdate',
        'contact_no',
        'precinct_no',
        'gender',
        'remarks',
        'position',
        'parent_id',
        'municipality',
        'barangay',
        'purok'
    ];


    public function members()
    {
        return $this->hasMany(ChristianCommunity::class, 'parent_id');
    }
    public function leader()
    {
        return $this->belongsTo(ChristianCommunity::class, 'parent_id');
    }
}
