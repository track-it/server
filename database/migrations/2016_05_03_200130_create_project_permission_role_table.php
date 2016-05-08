<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectPermissionRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_permission_role', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_role_id')->unsigned()->index();
            $table->foreign('project_role_id')->references('id')->on('project_roles');
            $table->integer('project_permission_id')->unsigned()->index();
            $table->foreign('project_permission_id')->references('id')->on('project_permissions');
            
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
        Schema::drop('project_permission_role');
    }
}
