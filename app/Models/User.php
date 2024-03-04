<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'agency_id',
        'profile_id',
        'is_enabled'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_enabled' => 'boolean',
        'password' => 'hashed'
    ];

    //nombre del rol
    public function getRoleAttribute($value)
    {
        switch ($value) {
            case '001':
                return 'Administrador';
                break;
            case '002':
                return 'Operador';
                break;
            case '003':
                return 'Instructor';
                break;
        }
    }

    public function profile()
    {
        switch ($this->role) {
            case '001':
                return $this->belongsTo(Administrator::class);
                break;
            case '002':
                return $this->belongsTo(Operator::class);
                break;
            case '003':
                return $this->belongsTo(Instructor::class);
                break;
        }
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    //registrar usuario
    static public function createAccount($request, $profile)
    {
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;
        $user->agency_id = $request->agency_id;
        $user->profile_id = $profile->id;
        $user->save();
        return $user;
    }

    //actualizar usuario
    static public function updateAccount($request, $profile)
    {
        $user = User::find($request->user_id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->agency_id = $request->agency_id;
        $user->profile_id = $profile->id;
        $user->save();
        return $user;
    }
    //eliminar usuario
    static public function deleteAccount($id)
    {
        $user = User::where('profile_id', $id)->first();
        $user->delete();
    }
}
