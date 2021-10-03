<?php


use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent')->unsigned()->default(0);
            $table->string('name', 20);
            $table->string('emoji', 20);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

//        Group::truncate();
        DB::table('groups')->insert([

            ['id' => 31, 'name' => 'مد‌و‌پوشاک', 'emoji' => '👔'],
            ['id' => 40, 'name' => 'آرایشی‌بهداشتی', 'emoji' => '💄'],


        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
