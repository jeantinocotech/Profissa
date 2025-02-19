<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('advisor_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_profiles_advisor');
            $table->unsignedBigInteger('id_skills');
            $table->timestamp('created_at')->useCurrent();

            // Unique constraint
            $table->unique(['id_profiles_advisor', 'id_skills'], 'advisor_skill_unique');

            // Foreign keys
            $table->foreign('id_profiles_advisor', 'FK_advisor_skills_profile')
                ->references('id')
                ->on('profiles_advisor')
                ->onDelete('cascade');

            $table->foreign('id_skills', 'FK_advisor_skills_skills')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');

            // Indexes
            $table->index('id_profiles_advisor', 'FK_advisor_skills_profile_idx');
            $table->index('id_skills', 'FK_advisor_skills_skills_idx');
        });
    }

    public function down(): void {
        Schema::dropIfExists('advisor_skills');
    }
};

