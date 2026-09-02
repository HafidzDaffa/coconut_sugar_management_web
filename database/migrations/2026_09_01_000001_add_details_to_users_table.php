<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_code', 30)->nullable()->unique()->after('id');
            $table->string('phone_number', 25)->nullable()->after('email');
            $table->foreignId('branch_id')
                  ->nullable()
                  ->after('role_id')
                  ->constrained('branches')
                  ->nullOnDelete();
            $table->enum('gender', ['male', 'female'])->nullable()->after('phone_number');
            $table->string('avatar', 255)->nullable()->after('gender');
            $table->text('address')->nullable()->after('avatar');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('address');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn([
                'employee_code',
                'phone_number',
                'branch_id',
                'gender',
                'avatar',
                'address',
                'status',
                'last_login_at',
                'deleted_at',
            ]);
        });
    }
};
