<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); $table->string('source'); $table->string('source_id')->nullable();
            $table->string('company'); $table->string('title'); $table->string('location')->nullable();
            $table->text('description')->nullable(); $table->text('url'); $table->timestamp('posted_at')->nullable();
            $table->string('hash')->unique(); $table->unsignedTinyInteger('match_score')->nullable();
            $table->json('match_reasons')->nullable(); $table->json('match_concerns')->nullable();
            $table->timestamp('emailed_at')->nullable(); $table->timestamps();
            $table->index(['source','source_id']); $table->index('match_score');
        });
    }
    public function down(): void { Schema::dropIfExists('jobs'); }
};
