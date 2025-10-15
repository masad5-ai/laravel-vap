<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->string('driver')->default('builtin')->after('type');
            $table->string('endpoint_url')->nullable()->after('driver');
            $table->string('endpoint_method')->nullable()->after('endpoint_url');
            $table->json('endpoint_headers')->nullable()->after('settings');
            $table->text('endpoint_payload_template')->nullable()->after('endpoint_headers');
        });
    }

    public function down(): void
    {
        Schema::table('integrations', function (Blueprint $table) {
            $table->dropColumn([
                'driver',
                'endpoint_url',
                'endpoint_method',
                'endpoint_headers',
                'endpoint_payload_template',
            ]);
        });
    }
};
