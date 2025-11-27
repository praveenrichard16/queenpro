<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\HasDatabaseNotifications;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CustomerAddress;
use App\Services\PhoneNumberService;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable, HasDatabaseNotifications;

	protected $fillable = [
		'name',
		'email',
		'password',
		'phone',
		'phone_country_code',
		'designation',
		'avatar_path',
		'is_staff',
		'is_admin',
		'is_super_admin',
		'marketing_opt_in',
		'timezone',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
		'is_staff' => 'boolean',
		'is_admin' => 'boolean',
		'is_super_admin' => 'boolean',
		'marketing_opt_in' => 'boolean',
	];

	/**
	 * Set phone number and automatically add country code if missing
	 */
	public function setPhoneAttribute($value): void
	{
		if (empty($value)) {
			$this->attributes['phone'] = null;
			return;
		}

		$normalized = PhoneNumberService::normalize($value, $this->phone_country_code);
		
		$this->attributes['phone'] = $normalized['phone'];
		
		// Update country code if it was detected/added
		if ($normalized['country_code'] && empty($this->phone_country_code)) {
			$this->attributes['phone_country_code'] = $normalized['country_code'];
		}
	}

	/**
	 * Get formatted phone number
	 */
	public function getFormattedPhoneAttribute(): ?string
	{
		if (empty($this->phone)) {
			return null;
		}

		return PhoneNumberService::format($this->phone, $this->phone_country_code);
	}

	public function getAvatarUrlAttribute(): ?string
	{
		return $this->avatar_path
			? asset('storage/'.$this->avatar_path)
			: null;
	}

	public function getHasAvatarAttribute(): bool
	{
		return !empty($this->avatar_path);
	}

	public function getAvatarInitialsAttribute(): string
	{
		if (empty($this->name)) {
			return '?';
		}

		$nameParts = explode(' ', trim($this->name));
		
		if (count($nameParts) >= 2) {
			// First letter of first name + first letter of last name
			return strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1));
		} else {
			// First two letters of the name
			return strtoupper(substr($this->name, 0, 2));
		}
	}

	public function orders()
	{
		return $this->hasMany(Order::class, 'user_id');
	}

	// Legacy relationship for orders linked by email (for backward compatibility)
	public function ordersByEmail()
	{
		return $this->hasMany(Order::class, 'customer_email', 'email');
	}

	public function cartSessions()
	{
		return $this->hasMany(CartSession::class);
	}

	public function journeyEvents()
	{
		return $this->hasMany(CustomerJourneyEvent::class);
	}

	public function supportTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Ticket::class, 'customer_id');
	}

	public function assignedTickets(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Ticket::class, 'assigned_to');
	}

	public function addresses(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(CustomerAddress::class);
	}

	public function affiliate()
	{
		return $this->hasOne(Affiliate::class);
	}

	public function activityLogs()
	{
		return $this->hasMany(ActivityLog::class);
	}

	public function modules()
	{
		return $this->hasMany(UserModule::class);
	}

	public function hasModuleAccess(string $moduleName): bool
	{
		// Super admins and admins have access to all modules
		if ($this->is_super_admin || $this->is_admin) {
			return true;
		}

		// Staff users need explicit module assignment
		if ($this->is_staff) {
			return $this->modules()->where('module_name', $moduleName)->exists();
		}

		return false;
	}

	public function getAssignedModulesAttribute(): array
	{
		return $this->modules()->pluck('module_name')->toArray();
	}

	public function getLastActivityAttribute()
	{
		return $this->activityLogs()->latest()->first();
	}

	public function getTotalActivitiesAttribute(): int
	{
		return $this->activityLogs()->count();
	}
}
