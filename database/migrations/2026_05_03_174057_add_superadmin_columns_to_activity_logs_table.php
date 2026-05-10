<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('user_id')->nullable()->index();

                $table->string('module')->index();
                $table->string('action')->index();
                $table->text('description');

                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();

                $table->json('properties')->nullable();

                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();

                $table->timestamps();

                $table->index(['subject_type', 'subject_id']);
                $table->index('created_at');
            });

            return;
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            }

            if (! Schema::hasColumn('activity_logs', 'module')) {
                $table->string('module')->nullable()->index();
            }

            if (! Schema::hasColumn('activity_logs', 'action')) {
                $table->string('action')->nullable()->index();
            }

            if (! Schema::hasColumn('activity_logs', 'description')) {
                $table->text('description')->nullable();
            }

            if (! Schema::hasColumn('activity_logs', 'subject_type')) {
                $table->string('subject_type')->nullable();
            }

            if (! Schema::hasColumn('activity_logs', 'subject_id')) {
                $table->unsignedBigInteger('subject_id')->nullable();
            }

            if (! Schema::hasColumn('activity_logs', 'properties')) {
                $table->json('properties')->nullable();
            }

            if (! Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->string('ip_address', 45)->nullable();
            }

            if (! Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable();
            }

            if (! Schema::hasColumn('activity_logs', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'module')) {
                $table->dropColumn('module');
            }

            if (Schema::hasColumn('activity_logs', 'action')) {
                $table->dropColumn('action');
            }

            if (Schema::hasColumn('activity_logs', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('activity_logs', 'subject_type')) {
                $table->dropColumn('subject_type');
            }

            if (Schema::hasColumn('activity_logs', 'subject_id')) {
                $table->dropColumn('subject_id');
            }

            if (Schema::hasColumn('activity_logs', 'properties')) {
                $table->dropColumn('properties');
            }

            if (Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->dropColumn('ip_address');
            }

            if (Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->dropColumn('user_agent');
            }
        });
    }
};