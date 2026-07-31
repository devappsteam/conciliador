<?php

namespace App\Modules\Core\Auth\Services;

use App\Modules\Core\User\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        //protected AuditService $auditService
    ) {}

    /**
     * Processa a autenticação baseada em sessão/cookie.
     */
    public function authenticate(array $credentials)
    {
        // Utiliza o guard nativo 'web' para autenticação baseada em Cookies (Sanctum SPA)
        if (!Auth::guard('web')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas estão incorretas.'],
            ]);
        }

        /** @var \App\Models\User $user */
        $user = Auth::guard('web')->user();

        request()->session()->regenerate();

        $this->userRepository->updateLastLogin($user->id);

        /*
        $this->auditService->log([
            'user_id'        => $user->id,
            'event'          => 'login',
            'auditable_type' => get_class($user),
            'auditable_id'   => $user->id,
            'old_values'     => null,
            'new_values'     => json_encode(['last_login_at' => now()->toDateTimeString()]),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
        */
        return $user;
    }

    /**
     * Destrói a sessão e invalida o token CSRF.
     */
    public function logout($request): void
    {
        /*
        $user = $request->user();

        if ($user) {

            // Auditoria do logout antes de destruir a sessão
            $this->auditService->log([
                'user_id'        => $user->id,
                'event'          => 'logout',
                'auditable_type' => get_class($user),
                'auditable_id'   => $user->id,
                'old_values'     => null,
                'new_values'     => null,
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent(),
            ]);
        }
        */

        Auth::guard('web')->logout();

        // Invalida a sessão atual no servidor
        $request->session()->invalidate();

        // Regenera o token CSRF para prevenir reutilização maliciosa da sessão limpa
        $request->session()->regenerateToken();
    }
}
