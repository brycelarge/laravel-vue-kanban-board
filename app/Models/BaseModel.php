<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use ZipArchive;

/**
 * App\Models\BaseModel
 *
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    public $timestamps = true;

    protected $guarded = ['id'];

    /**
     * @throws \Spatie\DbDumper\Exceptions\CannotStartDump
     * @throws \Spatie\DbDumper\Exceptions\DumpFailed
     */
    public static function dbDump(): File
    {
        // I dont think its needed to pass in a path
        $path = storage_path('dump.sql');
        $zipPath = storage_path('dump.zip');

        \Spatie\DbDumper\Databases\MySql::create()
            ->setDbName(config('database.connections.mysql.database'))
            ->setUserName(config('database.connections.mysql.username'))
            ->setPassword(config('database.connections.mysql.password'))
            ->dumpToFile($path);

        // Zip all the files
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $file = new File($path);
        $zip->addFile($file->getRealPath(), $file->getFilename());
        $zip->close();

        // Once files are zipped no need to keep the files
        if (file_exists($path)) {
            @unlink($path);
        }

        return new File($zipPath);
    }
}
