<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeyCompanyContact extends Model
{
    protected $table = 'key_company_contacts';
    protected $primaryKey = 'key_contact_id'; // or whatever yours is
    public $timestamps = true;

    public function project()
    {
        return $this->belongsTo(SolarProject::class, 'solar_project_id');
    }
}
