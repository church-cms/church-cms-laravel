<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('church_id')->unsigned()->nullable();
            $table->foreign('church_id')->references('id')->on('church');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('birth_firstname')->nullable();
            $table->string('birth_lastname')->nullable();
            $table->enum('gender', ['male', 'female', 'transgender']);
            $table->date('date_of_birth')->nullable();
            $table->enum('was_baptized', ['yes', 'no'])->nullable();
            $table->date('baptism_date')->nullable();
            $table->enum('profession', ['admin', 'business', 'doctor', 'engineer', 'government_employee', 'home_maker', 'lawyer', 'pastor', 'police', 'professionals', 'self_employed', 'student', 'teacher', 'others', 'guest', 'preacher'])->nullable();
            $table->string('sub_occupation')->nullable();
            $table->text('address')->nullable();
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('states');
            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('pincode')->nullable();
            $table->enum('membership_type', ['member', 'guest'])->nullable();
            $table->date('membership_start_date')->nullable();
            $table->longtext('membership_end_date')->nullable();
            $table->string('family')->nullable();
            $table->string('relation')->nullable();
            $table->enum('marriage_status', ['single', 'married', 'ended_by_death', 'ended_by_divorce', 'separated'])->nullable();
            $table->date('marriage_start_date')->nullable();
            $table->date('marriage_end_date')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->longtext('notes')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('status', ['active', 'inactive', 'exit'])->default('active');
            $table->longText('description')->nullable();
            $table->string('facebook_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('userprofiles');
    }
};
