<?php

namespace App\Models;

use App\Enums\EmployeeStatus as EnumsEmployeeStatus;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Spatie\Permission\Traits\HasRoles;
use Yebor974\Filament\RenewPassword\Contracts\RenewPasswordContract;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use Yebor974\Filament\RenewPassword\Traits\RenewPassword;

class Employee extends Authenticatable implements RenewPasswordContract, FilamentUser
{
    use Notifiable, HasFactory, HasRoles, RenewPassword;

    protected $fillable = [
        'username', 'nip', 'nip_intranet', 'name',
        'title_pre', 'title_post', 'birth_place', 'birth_date', 'gender_id',
        'religion_id', 'education_id', 'employee_status_id', 'rank_id',
        'tmt_rank', 'position_id',
        'tmt_position', 'tmt_work', 'tmt_pns', 'karpeg_number', 'ktp_number', 'askes_number',
        'npwp', 'address', 'phone', 'mobile', 'email', 'image', 'thumbnail',
        'password', 'can_edited', 'is_active', 'last_sync_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active === true;
    }
    
    protected $appends = ['slug']; // agar otomatis tersedia saat toArray()

    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->name) . ($this->id !== '' ? "-{$this->id}" : '');
    }

    public static function idFromSlug(string $slug): ?int
    {
        $parts = explode('-', $slug);
        $last  = end($parts);
        return ctype_digit($last) ? (int) $last : null;
    }

    public function needRenewPassword(): bool
    {
        $plugin = RenewPasswordPlugin::get();

        return
            (
                !is_null($plugin->getPasswordExpiresIn())
                && Carbon::parse($this->{$plugin->getTimestampColumn()})->addDays($plugin->getPasswordExpiresIn()) < now()
            ) || (
                $plugin->getForceRenewPassword()
                && $this->{$plugin->getForceRenewColumn()}
            );
    }

    public function getNicknameAttribute()
    {
        if (strlen($this->name) < 10) return $this->name;

        $nameArray = explode(' ', $this->name);

        $nickname = '';

        foreach ($nameArray as $key => $name) {

            $nickname .= ($key === array_key_first($nameArray))
                ? $name
                : ' ' . substr($nameArray[$key], 0, 1) . '.';
        }

        return $nickname;
    }

    public function getNameWithTitleAttribute()
    {
        $name = Str::headline($this->name);

        if ($this->title_pre) $name = $this->title_pre . ' ' .  $name;
        if ($this->title_post) $name = $name . ', ' . $this->title_post;

        return $name;
    }

    public function getBirthDateFormatLongAttribute()
    {
        return $this->birth_date
            ? Carbon::parse($this->birth_date)->isoFormat('D MMMM YYYY')
            : null;
    }

    public function getBirthDateFormatShortAttribute()
    {
        return $this->birth_date
            ? Carbon::parse($this->birth_date)->isoFormat('D MMM YYYY')
            : null;
    }

    public function getTmtRankFormatLongAttribute()
    {
        return $this->tmt_rank
            ? Carbon::parse($this->tmt_rank)->isoFormat('D MMMM YYYY')
            : null;
    }

    public function getTmtRankFormatShortAttribute()
    {
        return $this->tmt_rank
            ? Carbon::parse($this->tmt_rank)->isoFormat('D MMM YYYY')
            : null;
    }

    public function getTmtPositionFormatLongAttribute()
    {
        return $this->tmt_position
            ? Carbon::parse($this->tmt_position)->isoFormat('D MMMM YYYY')
            : null;
    }

    public function getTmtPositionFormatShortAttribute()
    {
        return $this->tmt_position
            ? Carbon::parse($this->tmt_position)->isoFormat('D MMM YYYY')
            : null;
    }

    public function getCanModifyPersonalAttribute()
    {
        return $this->can_edited;
    }

    public function getCanModifyProfessionAttribute()
    {
        return $this->can_edited;
    }

    public function getCanModifyEducationAttribute()
    {
        return $this->can_edited;
    }

    public function getCanModifyRankAttribute()
    {
        return $this->can_edited && in_array($this->employee_status_id, [EnumsEmployeeStatus::CPNS->value, EnumsEmployeeStatus::PNS->value, EnumsEmployeeStatus::PPPK->value]);
    }

    public function getCanModifyPositionAttribute()
    {
        return $this->can_edited;
    }

    public function getIsSelfAttribute()
    {
        return $this->id == Auth::user()->id;
    }

    public function getTmtWorkFormatLongAttribute()
    {
        return $this->tmt_work
            ? Carbon::parse($this->tmt_work)->isoFormat('D MMMM YYYY')
            : null;
    }

    public function getTmtWorkFormatShortAttribute()
    {
        return $this->tmt_work
            ? Carbon::parse($this->tmt_work)->isoFormat('D MMM YYYY')
            : null;
    }

    public function getTmtPnsFormatLongAttribute()
    {
        return $this->tmt_pns
            ? Carbon::parse($this->tmt_pns)->isoFormat('D MMMM YYYY')
            : null;
    }

    public function getTmtPnsFormatShortAttribute()
    {
        return $this->tmt_pns
            ? Carbon::parse($this->tmt_pns)->isoFormat('D MMM YYYY')
            : null;
    }

    public function getRankLatestAttribute()
    {
        if (in_array($this->employee_status_id, [EnumsEmployeeStatus::CPNS->value, EnumsEmployeeStatus::PNS->value, EnumsEmployeeStatus::PPPK->value])) {
            return $this->rank->label . '/' . $this->rank->name;

        } else {
            return null;

        }
    }

    public function getTmtWorkDurationAttribute()
    {
        if ($this->tmt_work) {
            $tmtWork = Carbon::parse($this->tmt_work);

            $diff = Carbon::now()->diff($tmtWork);

            return $diff->y . ' tahun ' . $diff->m . ' bulan';

        } else {
            return null;

        }
    }

    public function status()
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_status_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }

    public function employeeStatus()
    {
        return $this->belongsTo(EmployeeStatus::class);
    }

    public function scopeHasRole($query, $role)
    {
        return in_array($role->id, $query->roles->pluck('id', 'id')->all());
    }

    public function borrowedDocuments()
    {
        return $this->belongsToMany(Document::class, 'document_employee')
                    ->withPivot([
                        'borrow_date',
                        'due_date',
                        'return_date',
                        'status',
                        'needs',
                        'token',
                        'notes'
                    ])
                    ->withTimestamps();
    }
}
