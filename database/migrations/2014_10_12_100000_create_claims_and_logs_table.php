<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimsAndLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Kolom Formulir Baru dari User
            $table->string('nama_lengkap');
            $table->text('alamat');
            $table->string('no_telepon');
            $table->string('email_kontak');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('perencanaan_hidup'); // (Jiwa / Kesehatan / Kendaraan)
            $table->decimal('nominal_asuransi', 15, 2);
            $table->integer('tinggi_badan');
            $table->integer('berat_badan');
            $table->text('masalah_kesehatan')->nullable();
            $table->decimal('total_asuransi_jiwa', 15, 2);

            // Status Lifecycle
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });

        Schema::create('claim_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->string('from_status');
            $table->string('to_status');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('claims_and_logs');
    }
}
