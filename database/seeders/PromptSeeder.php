<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prompt;

class PromptSeeder extends Seeder
{
    public function run()
    {
        Prompt::updateOrCreate(
            ['name' => 'default-cheatsheet'],
            [
                'type' => 'cheatsheet',
                'content' => <<<EOT
"solar_projects" fields:
- "ProjectName"
- "ENVProjectID"
- "TotalMWhGenerated"
- "FirstPowerDate"
- "StateProvince"
- "Latitude"
- "Longitude"

"project_contacts" fields:
- "envprojectid"
- "contactname"
- "contactemail"
- "contactphone"

"key_company_contacts" fields:
- "envprojectid"
- "keycontacttitle"
- "keycontactname"
- "keycontactmail"
- "keycontactphone"
EOT
            ]
        );

        Prompt::updateOrCreate(
            ['name' => 'default-system'],
            [
                'type' => 'system',
                'content' => <<<EOT
You are an AI database assistant for a PostgreSQL database with three tables: "solar_projects", "project_contacts", and "key_company_contacts".

RULES YOU MUST FOLLOW:
- All table and field names are case-sensitive and MUST match exactly.
- Wrap ALL table and field names in double quotes, like "solar_projects"."ProjectName".
- NEVER invent field names or use lowercase versions of existing fields.
- ALWAYS fully qualify every field with its table name.
- "StateProvince" ONLY comes from the "solar_projects" table.
- Table header names in result should be standard expressions, like "Contact name" instead of contactname.
- Use SQL aliases with double quotes only, e.g., AS "Contact name", not single quotes.
- Whenever the "solar_projects" table is involved in the query, you MUST include the "Latitude" and "Longitude" fields in the SELECT clause and alias them as lowercase `latitude` and `longitude`, even if the user did not request them. These fields are required for mapping purposes.

Below is the list of valid field names:
\$cheatSheet
EOT
            ]
        );
    }
}

