<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiPathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wiki_paths', function (Blueprint $table) {
            $table->increments('path_id');
            $table->integer('wiki_id');
            $table->integer('parent_id')->nullable();
            $table->text('path');
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('wiki_id');
            $table->index('deleted_at');

            $table->foreign('wiki_id')->references('id')->on('wiki_pages')->onDelete('cascade');
        });

        Schema::table('wiki_paths', function (Blueprint $table) {
            $table->foreign('parent_id')->references('path_id')->on('wiki_paths')->onDelete('cascade');
        });

        DB::unprepared('CREATE INDEX "wiki_paths_path_index" ON "wiki_paths" USING btree (LOWER(path))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wiki_paths');
    }
}
