<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelledAtToMeetingRequestTable extends Migration
{
    public function up()
    {
        Schema::table('meeting_requests', function (Blueprint $table) {
            // Adiciona a coluna cancelled_at do tipo datetime
            $table->datetime('canceled_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('meeting_requests', function (Blueprint $table) {
            // Remove a coluna cancelled_at
            $table->dropColumn('canceled_at');
        });
    }
}
