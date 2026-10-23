<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Spatie\Permission\Models\Role;

class LocalAdminUsersSeeder extends Seeder
{
    /**
     * Crea i due account usati per provare il pannello Filament in locale.
     */
    public function run(): void
    {
        // Impedisce di creare account di prova su staging o produzione.
        if (! app()->environment('local')) {
            throw new RuntimeException(
                'LocalAdminUsersSeeder può essere eseguito soltanto con APP_ENV=local.'
            );
        }

        $this->ensureRolesExist();

        DB::transaction(function (): void {
            $this->createLocalUser('super_admin');
            $this->createLocalUser('admin');
        });
    }

    /**
     * Crea o aggiorna un utente e gli assegna un solo ruolo.
     */
    private function createLocalUser(string $roleName): void
    {
        /** @var array{name: mixed, lastname: mixed, email: mixed, password: mixed} $data */
        $data = config("local-users.{$roleName}");

        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException(
                "Configura un indirizzo email valido per LOCAL_{$this->envRoleName($roleName)}_EMAIL."
            );
        }

        if (mb_strlen($password) < 8) {
            throw new RuntimeException(
                "Configura LOCAL_{$this->envRoleName($roleName)}_PASSWORD nel file .env con almeno 8 caratteri."
            );
        }

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => trim((string) ($data['name'] ?? '')),
                'lastname' => trim((string) ($data['lastname'] ?? '')),
                'password' => Hash::make($password),
            ],
        );

        // syncRoles evita che un account locale mantenga ruoli aggiuntivi.
        $user->syncRoles([$roleName]);

        $this->command?->info("Utente {$roleName} pronto: {$email}");
    }

    /**
     * I ruoli e i relativi permessi devono essere preparati prima degli utenti.
     */
    private function ensureRolesExist(): void
    {
        $missingRoles = collect(['super_admin', 'admin'])
            ->reject(fn (string $roleName): bool => Role::query()
                ->where('name', $roleName)
                ->where('guard_name', 'web')
                ->exists());

        if ($missingRoles->isNotEmpty()) {
            throw new RuntimeException(
                'Ruoli mancanti: '.$missingRoles->implode(', ').
                '. Esegui prima php artisan db:seed --class=RolePermissionSeeder.'
            );
        }
    }

    /**
     * Converte super_admin in SUPER_ADMIN per comporre il nome della variabile.
     */
    private function envRoleName(string $roleName): string
    {
        return strtoupper($roleName);
    }
}
