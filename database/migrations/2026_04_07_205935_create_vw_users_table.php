<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(
            "CREATE OR REPLACE VIEW vw_users AS
            SELECT
                u.id,
                u.username,
                u.email,
                u.active AS active,
                u.created_at AS created_at,
                r.id AS role_id,
                r.role AS role_name,
                r.description AS role_description,
                r.read AS role_read,
                r.create AS role_create,
                r.update AS role_update,
                r.delete AS role_delete,
                r.more_permissions AS role_more_permissions,
                c.id AS casilla_id,
                c.type AS casilla_type,
                c.district AS casilla_district,
                c.perimeter AS casilla_perimeter,
                c.place AS casilla_place,
                c.location AS casilla_location,
                c.active AS casilla_active
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            LEFT JOIN casillas c ON u.casilla_id = c.id
            WHERE u.deleted_at IS NULL;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS vw_users');
    }
};