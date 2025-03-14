<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE VIEW vista_persona AS
            SELECT
                p.id AS id_persona,
                p.nombre AS nombre_persona,
                p.apellido_paterno AS apellido_paterno_persona,
                p.apellido_materno AS apellido_materno_persona
            FROM persona p
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS vista_persona");
    }
};
