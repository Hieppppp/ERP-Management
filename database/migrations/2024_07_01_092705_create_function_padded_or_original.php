<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            DROP FUNCTION IF EXISTS PaddedOrOriginalIfShorter;
            CREATE FUNCTION PaddedOrOriginalIfShorter(
                str VARCHAR(255),
                length INT,
                pad_str CHAR(1)
            )
            RETURNS VARCHAR(255)
            DETERMINISTIC
            BEGIN
                IF LENGTH(str) >= length THEN
                    RETURN str COLLATE utf8mb4_bin;
                ELSE
                    RETURN LPAD(str, length, pad_str) COLLATE utf8mb4_bin;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS PaddedOrOriginalIfShorter');
    }
};
