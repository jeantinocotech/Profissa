<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInMeetingProposalsTable extends Migration
{
    public function up()
    {
        Schema::table('meeting_proposals', function (Blueprint $table) {
            // Atualize a coluna 'status' para incluir o novo status
            $table->enum('status', ['pending','accepted', 'declined', 'cancellation_requested'])
                  ->default('pending')
                  ->change();
        });
    }

    public function down()
    {
        Schema::table('meeting_proposals', function (Blueprint $table) {
            // Reverter para os valores anteriores
            $table->enum('status', ['pending','accepted', 'declined'])
                  ->default('pending')
                  ->change();
        });
    }
}


