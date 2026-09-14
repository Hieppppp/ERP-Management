<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->foreignId('requested_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 32)->default('draft')->index();
            $table->date('needed_at')->nullable();
            $table->text('note')->nullable();
            $table->text('approval_note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
        Schema::create('material_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->decimal('requested_quantity', 15, 3);
            $table->decimal('issued_quantity', 15, 3)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['material_request_id', 'product_id']);
        });
        Schema::create('material_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_request_id')->constrained()->restrictOnDelete();
            $table->foreignId('issued_by')->constrained('users')->restrictOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        Schema::create('material_issue_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_issue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_request_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_location_id')->constrained()->restrictOnDelete();
            $table->decimal('quantity', 15, 3);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('material_issue_lines');
        Schema::dropIfExists('material_issues');
        Schema::dropIfExists('material_request_items');
        Schema::dropIfExists('material_requests');
    }
};
