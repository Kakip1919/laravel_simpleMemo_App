<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateMemosTable extends Migration
{

    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText("content");
            $table->integer("user_id");
            $table->integer("status")->default("1");
            $table->timestamp("updated_at")->default(DB::raw("CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP"));
            $table->timestamp("created_at")->default(DB::raw("CURRENT_TIMESTAMP"));
        });
    }


    public function down()
    {
        Schema::dropIfExists('memos');
    }
}
