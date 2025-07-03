<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            
            // Couple Information
            $table->string('groom_name');
            $table->string('bride_name');
            $table->string('groom_email')->nullable();
            $table->string('bride_email')->nullable();
            $table->string('groom_phone')->nullable();
            $table->string('bride_phone')->nullable();
            
            // Photos
            $table->string('groom_photo')->nullable();
            $table->string('bride_photo')->nullable();
            $table->string('couple_photo')->nullable();
            
            // Wedding Details
            $table->date('wedding_date')->nullable();
            $table->string('venue_used')->nullable();
            $table->string('package_used')->nullable();
            
            // Review Content
            $table->integer('rating')->unsigned()->default(5); // 1-5 stars
            $table->string('title');
            $table->text('review_text');
            $table->json('service_ratings')->nullable(); // Detailed ratings for different aspects
            $table->boolean('would_recommend')->default(true);
            $table->text('favorite_aspect')->nullable();
            $table->text('improvement_suggestions')->nullable();
            
            // Admin Controls
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('display_on_website')->default(true);
            $table->boolean('social_media_consent')->default(false);
            
            // Metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_approved', 'display_on_website']);
            $table->index(['is_featured', 'rating']);
            $table->index('wedding_date');
            $table->index('rating');
            $table->index('created_at');

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};