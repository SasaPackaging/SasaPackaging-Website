<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use PDO;
class Setting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_settings';
    public $timestamps = false;
	
	/*
      |--------------------------------------------------------------------------
      | function to find value by key 
      |--------------------------------------------------------------------------
     */
    public static function findByKey($key, $default = null){

        // bound value (no SQL injection) and no crash when the key does not exist
        $row = DB::table('tbl_settings')->where('key_name', $key)->first();

        return $row ? $row->key_value : $default;
    }
    
    
}
