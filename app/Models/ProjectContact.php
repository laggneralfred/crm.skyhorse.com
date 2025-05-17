<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectContact extends Model
{
   
    protected $table = 'project_contacts';
    protected $primaryKey = 'contact_id'; // or whatever yours is
    public $timestamps = true;

    public function project()
    {
        return $this->belongsTo(SolarProject::class, 'solar_project_id');
    }
}