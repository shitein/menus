<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class MenuMappingAfterUpdateTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER `menu_mapping_after_update_trigger` AFTER UPDATE ON `menu_mapping` FOR EACH ROW
        BEGIN
                DECLARE GM_STATUS VARCHAR(255);
                IF NEW.menu_master_id <> OLD.menu_master_id         THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','menu_master_id',old.menu_master_id,new.menu_master_id,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF  NEW.menu_id <> OLD.menu_id   THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','menu_id',old.menu_id,new.menu_id,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.parent_id <> OLD.parent_id       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','parent_id',old.parent_id,new.parent_id,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.reference_id <> OLD.reference_id       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','reference_id',old.reference_id,new.reference_id,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.short_code <> OLD.short_code       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','short_code',old.short_code,new.short_code,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.sequence <> OLD.sequence       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','sequence',old.sequence,new.sequence,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.description <> OLD.description       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','description',old.description,new.description,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.url <> OLD.url       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','url',old.url,new.url,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.class <> OLD.class       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','class',old.class,new.class,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.font_icon_class <> OLD.font_icon_class       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','font_icon_class',old.font_icon_class,new.font_icon_class,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                ELSEIF NEW.is_deleted <> OLD.is_deleted       THEN
                SET GM_STATUS = insert_general_master_log('menu_mapping','is_deleted',old.is_deleted,new.is_deleted,CURRENT_USER,old.id,'192.168.43.1','W','0','0');
                END IF;
        END

        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP TRIGGER menu_mapping_after_update_trigger");
    }
}
