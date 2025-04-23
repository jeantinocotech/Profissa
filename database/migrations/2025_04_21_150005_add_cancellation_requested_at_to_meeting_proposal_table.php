<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('meeting_proposals', function (Blueprint $table) {
            // Adiciona o campo cancellation_requested_at do tipo datetime
            $table->datetime('cancellation_requested_at')->nullable()->after('advisor_comment');
        });
    }

    public function down()
    {
        Schema::table('meeting_proposals', function (Blueprint $table) {
            // Remove o campo cancellation_requested_at
            $table->dropColumn('cancellation_requested_at');
        });
    }
};
