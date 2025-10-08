<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms;
use App\Models\Instansi;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Illuminate\Database\Eloquent\Model;

use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getRoleFormComponent(),
                        $this->getnama(),
                        $this->getintansi(),
                        $this->getsatuan(),
                        $this->getusername(),
                        $this->getEmailFormComponent(),
                        $this->gettlpn(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        
                        
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Component
    {
        return Forms\Components\Hidden::make('role')
                            ->default('pelaksana');
    }
    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);

        $data['role'] = 'pelaksana'; 

        Role::firstOrCreate(['name' => $data['role']]);

        $user->assignRole($data['role']);

        return $user;
    }
    protected function getnama(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label('Nama Lengkap')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }
    protected function getusername(): Component
    {
        return Forms\Components\TextInput::make('username')
            ->label('Username')
            ->required()
            ->maxLength(255);
    }
    protected function getintansi(): Component
    {
        return Select::make('instansi_id')
            ->label('Instansi')
            ->options(instansi::pluck('nama', 'id'))
            ->required();
    }
    protected function getsatuan(): Component
    {
        return Forms\Components\TextInput::make('satuan')
            ->label('satuan')
            ->helperText('Contoh : Dinas Pendidikan')
            ->required()
            ->maxLength(255);
    }
    protected function gettlpn(): Component
    {
        return Forms\Components\TextInput::make('phone')
            ->label('No Telepon/ Wa aktif')
            ->required()
            ->maxLength(255);
    }
    

    // protected static string $view = 'filament.pages.auth.register';
}
