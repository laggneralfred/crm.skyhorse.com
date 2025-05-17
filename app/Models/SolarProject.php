<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarProject extends Model
{
    protected $table = 'solar_projects'; // good to be explicit
    protected $primaryKey = 'id'; // this is default, but let's be clear
    public $incrementing = true;
    public $timestamps = true; // or false if you're not using them

    public function projectContacts()
    {
        return $this->hasMany(ProjectContact::class, 'solar_project_id', 'id');
    }
    
    public function keyCompanyContacts()
    {
        return $this->hasMany(KeyCompanyContact::class, 'solar_project_id', 'id');
    }
    
}