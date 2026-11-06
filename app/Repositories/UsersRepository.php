<?php

namespace App\Repositories;

use App\Enums\UserTypes;
use App\Exceptions\NotifiableException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersRepository
{
    /**
     * store user
     *
     * @return void
     */
    public function store(array $data, int $type)
    {
        $this->validatesStore($data, $type);

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'cel' => $data['cel'] ? __('phoneCode').$data['cel'] : null,
            'cel_whatsapp' => $data['cel_whatsapp'] ? __('phoneCode').$data['cel_whatsapp'] : null,
            'dealership_id' => $data['dealership_id'] ?? null,
            'type' => $type,
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * @return void
     */
    public function update(Request $request, User $user)
    {
        $this->validatesUpdate($request->all(), $user->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'cel' => $request->get('cel') ? __('phoneCode').$request->get('cel') : null,
            'cel_whatsapp' => $request->get('cel_whatsapp') ? __('phoneCode').$request->get('cel_whatsapp') : null,
            'type' => $request->get('type'),
        ]);

        // El usuario puede actualizar su password
        if ($request->filled('new_password')) {
            if (Hash::check($request->get('old_password'), $request->user()->password)) {
                $request->user()->fill([
                    'password' => Hash::make($request->new_password),
                ])->save();
            } else {
                throw new NotifiableException(__('auth.password_confirmation'));
            }
        }
    }

    /**
     * @return void
     */
    protected function validatesStore(array $data, int $type)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ];
        if ($this->isDealerLevel($type)) {
            $rules['dealership_id'] = 'required';
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new NotifiableException($validator->errors()->first());
        }
    }

    /**
     * @return void
     */
    protected function validatesUpdate(array $data, int $user_id)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user_id)],
            'new_password' => 'nullable|min:6',
        ]);
        if ($validator->fails()) {
            throw new NotifiableException($validator->errors()->first());
        }
    }

    /**
     * says if it is dealer level
     *
     * @return bool
     */
    protected function isDealerLevel(int $type)
    {
        $dealerLevel = [UserTypes::Owner, UserTypes::Seller, UserTypes::Manager];

        return in_array($type, $dealerLevel);
    }
}
