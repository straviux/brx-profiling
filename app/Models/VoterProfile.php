<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class VoterProfile extends Model
{
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
        'barangay',
        'purok',
        'parent_id'
    ];

    public function leader()
    {
        return $this->belongsTo(VoterProfile::class, 'parent_id');
    }
}
