<?php

use App\Models\ObjectsLogsModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(ObjectsLogsModel::TABLE_NAME, function (Blueprint $table) {
            $table->increments(ObjectsLogsModel::COLUMN_ID);
            $table->string(ObjectsLogsModel::COLUMN_KEY);
            $table->string(ObjectsLogsModel::COLUMN_VALUE);
            $table->timestamp(ObjectsLogsModel::COLUMN_CREATED_AT);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(ObjectsLogsModel::TABLE_NAME);
    }
}
