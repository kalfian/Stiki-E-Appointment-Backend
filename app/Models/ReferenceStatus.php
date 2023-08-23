<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;
    const STATUS_DRAFT = 2;

    const STATUS_IMPORT_ON_PROGRESS = 100;
    const STATUS_IMPORT_SUCCESS = 101;
    const STATUS_IMPORT_FAILED = 102;
    const STATUS_IMPORT_DUPLICATE = 103;
    const STATUS_IMPORT_INVALID = 104;

    public static function translateStatus($status) {
        switch ($status) {
            case self::STATUS_ACTIVE:
                return 'Active';
            case self::STATUS_INACTIVE:
                return 'Inactive';
            case self::STATUS_BANNED:
                return 'Banned';
            case self::STATUS_DRAFT:
                return 'Draft';

            case self::STATUS_IMPORT_ON_PROGRESS:
                return 'Import On Progress';
            case self::STATUS_IMPORT_SUCCESS:
                return 'Import Success';
            case self::STATUS_IMPORT_FAILED:
                return 'Import Failed';
            case self::STATUS_IMPORT_DUPLICATE:
                return 'Import Duplicate';
            case self::STATUS_IMPORT_INVALID:
                return 'Import Invalid';

            default:
                return 'Unknown';
        }
    }

    public static function translateStatusColor($status) {
        switch ($status) {
            case self::STATUS_ACTIVE:
                return 'success';
            case self::STATUS_INACTIVE:
                return 'warning';
            case self::STATUS_BANNED:
                return 'danger';
            case self::STATUS_DRAFT:
                return 'secondary';

            case self::STATUS_IMPORT_ON_PROGRESS:
                return 'info';
            case self::STATUS_IMPORT_SUCCESS:
                return 'success';
            case self::STATUS_IMPORT_FAILED:
                return 'danger';
            case self::STATUS_IMPORT_DUPLICATE:
                return 'warning';
            case self::STATUS_IMPORT_INVALID:
                return 'danger';

            default:
                return 'secondary';
        }
    }

}
