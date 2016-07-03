<?php

use App\Models\ObjectsModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(ObjectsModel::TABLE_NAME, function (Blueprint $table) {
            $table->increments(ObjectsModel::COLUMN_ID);
            $table->string(ObjectsModel::COLUMN_KEY);
            $table->string(ObjectsModel::COLUMN_VALUE);
            $table->timestamp(ObjectsModel::COLUMN_UPDATED_AT)
                ->nullable()
                ->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp(ObjectsModel::COLUMN_CREATED_AT)->useCurrent();
            $table->unique(ObjectsModel::COLUMN_KEY);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(ObjectsModel::TABLE_NAME);
    }
}
