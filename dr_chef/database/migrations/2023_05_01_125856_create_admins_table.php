<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id('admin_id');
            $table->string('admin_name');
            $table->string('admin_email')->unique();
            $table->string('admin_password');
            $table->string('admin_picture');
        });

        $data = [
            [
                'admin_name' => 'Muhammad Sohaib Khan',
                'admin_email' => 'muhammadsohaib@gmail.com',
                'admin_password' => Hash::make('admin'),
                'admin_picture' => 'sohaib.jpg',
            ]
        ];

        Admin::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
};
