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
        Schema::create('meeting_proposals', function (Blueprint $table) {
            $table->id();
            $table->integer('id_meeting_request');
            $table->dateTime('proposed_datetime');
            $table->text('finder_comment')->nullable();
            $table->text('advisor_comment')->nullable();
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->boolean('confirmed_by_finder')->default(false);
            $table->boolean('confirmed_by_advisor')->default(false);
            $table->timestamps();
        
            $table->foreign('id_meeting_request')->references('id')->on('meeting_requests')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_proposals');
    }
};
