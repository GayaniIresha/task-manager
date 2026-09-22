<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('status')->default('todo')->after('description');
            $table->date('due_date')->nullable()->after('status');
        });

        // Migrate existing completed boolean to status string
        DB::table('tasks')->where('completed', true)->update(['status' => 'done']);
        DB::table('tasks')->where('completed', false)->update(['status' => 'todo']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('completed');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('completed')->default(false);
        });

        DB::table('tasks')->where('status', 'done')->update(['completed' => true]);

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('due_date');
        });
    }
};
