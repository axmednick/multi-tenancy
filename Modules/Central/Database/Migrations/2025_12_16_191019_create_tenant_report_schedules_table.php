<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_report_schedules', function (Blueprint $table) {
            $table->id();

            $table->string('tenant_id')->unique();
            $table->timestamp('last_executed_at')->nullable();
            $table->string('frequency')->default('weekly');
            $table->integer('executed_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_report_schedules');
    }
};
