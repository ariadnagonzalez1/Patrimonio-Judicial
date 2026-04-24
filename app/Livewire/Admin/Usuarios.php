<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Validation\Rule;

class Usuarios extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $mostrarInactivos = false;
    public $showModal = false;
    public $showConfirm = false;
    public $usuarioSeleccionado = null;

    public $nombre, $email, $password, $role_id, $modoEdicion = false;

    protected function rules()
    {
        $emailRule = $this->modoEdicion
            ? ['required', 'email', Rule::unique('users', 'email')->ignore($this->usuarioSeleccionado)]
            : ['required', 'email', 'unique:users,email'];

        $passwordRule = $this->modoEdicion
            ? ['nullable', 'string', 'min:6']
            : ['required', 'string', 'min:6'];

        return [
            'nombre' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => $passwordRule,
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMostrarInactivos()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('rol');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if (!$this->mostrarInactivos) {
            $query->where('activo', 1);
        }

        $usuarios = $query->orderBy('nombre')->paginate(10);
        $roles = Rol::orderBy('nombre')->get();

        return view('livewire.admin.usuarios', [
            'usuarios' => $usuarios,
            'roles' => $roles,
        ]);
    }

    // Crear nuevo usuario
    public function guardarUsuario()
    {
        $this->validate();

        User::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role_id' => $this->role_id,
            'activo' => 1,
        ]);

        $this->resetFormulario();
        session()->flash('message', 'Usuario creado correctamente ✅');
    }

    // Mostrar modal para editar
    public function editar($id)
    {
        $usuario = User::findOrFail($id);
        
        // 🛡️ PROTECCIÓN: No permitir editar al usuario autenticado
        if ($usuario->id === auth()->id()) {
            session()->flash('error', 'No puedes editar tu propia cuenta desde aquí. Usa tu perfil.');
            return;
        }
        
        $this->usuarioSeleccionado = $usuario->id;
        $this->nombre = $usuario->nombre;
        $this->email = $usuario->email;
        $this->role_id = $usuario->role_id;
        $this->password = '';
        $this->modoEdicion = true;
        $this->showModal = true;
    }

    // Actualizar usuario
    public function actualizarUsuario()
    {
        $this->validate();

        $usuario = User::findOrFail($this->usuarioSeleccionado);
        
        // 🛡️ PROTECCIÓN ADICIONAL: Verificar que no esté editando su propia cuenta
        if ($usuario->id === auth()->id()) {
            session()->flash('error', 'No puedes editar tu propia cuenta desde aquí.');
            $this->resetFormulario();
            return;
        }
        
        $usuario->update([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'password' => $this->password ? bcrypt($this->password) : $usuario->password,
        ]);

        $this->resetFormulario();
        session()->flash('message', 'Usuario actualizado correctamente ✏️');
    }

    // Mostrar modal de confirmación de baja
    public function confirmarBaja($id)
    {
        $usuario = User::findOrFail($id);
        
        // 🛡️ VALIDACIÓN 1: No puede darse de baja a sí mismo
        if ($usuario->id === auth()->id()) {
            session()->flash('error', 'No puedes dar de baja tu propia cuenta.');
            return;
        }
        
        // 🛡️ VALIDACIÓN 2: Verificar si es administrador
        $rolAdministrador = Rol::where('nombre', 'Administrador')->first();
        
        if ($rolAdministrador && $usuario->role_id === $rolAdministrador->id) {
            // Contar cuántos administradores activos hay
            $totalAdministradores = User::where('role_id', $rolAdministrador->id)
                                         ->where('activo', 1)
                                         ->count();
            
            if ($totalAdministradores <= 1) {
                session()->flash('error', 'No puedes dar de baja al único administrador activo del sistema.');
                return;
            }
        }
        
        // 🛡️ VALIDACIÓN 3 (OPCIONAL): Proteger al usuario ID=1 (admin principal)
        if ($usuario->id === 1) {
            session()->flash('error', 'El administrador principal no puede ser dado de baja.');
            return;
        }
        
        $this->usuarioSeleccionado = $id;
        $this->showConfirm = true;
    }

    // Dar de baja (confirmado)
    public function darDeBajaConfirmado()
    {
        if ($user = User::find($this->usuarioSeleccionado)) {
            
            // 🛡️ PROTECCIÓN ADICIONAL: Validar nuevamente antes de dar de baja
            if ($user->id === auth()->id()) {
                session()->flash('error', 'No puedes dar de baja tu propia cuenta.');
                $this->showConfirm = false;
                return;
            }
            
            $rolAdministrador = Rol::where('nombre', 'Administrador')->first();
            
            if ($rolAdministrador && $user->role_id === $rolAdministrador->id) {
                $totalAdministradores = User::where('role_id', $rolAdministrador->id)
                                             ->where('activo', 1)
                                             ->count();
                
                if ($totalAdministradores <= 1) {
                    session()->flash('error', 'No puedes dar de baja al único administrador activo.');
                    $this->showConfirm = false;
                    return;
                }
            }
            
            if ($user->id === 1) {
                session()->flash('error', 'El administrador principal no puede ser dado de baja.');
                $this->showConfirm = false;
                return;
            }
            
            $user->update(['activo' => 0]);
            session()->flash('message', 'Usuario dado de baja 🚫');
        }
        $this->showConfirm = false;
    }

    // Activar usuario
    public function activar($id)
    {
        if ($user = User::find($id)) {
            $user->update(['activo' => 1]);
            session()->flash('message', 'Usuario activado ✅');
        }
    }

    /**
     * 🛡️ MÉTODO AUXILIAR: Verificar si un usuario puede ser dado de baja
     * Útil para deshabilitar botones en la vista
     */
    public function puedeDarseDeBaja($usuarioId): bool
    {
        // No puede dar de baja su propia cuenta
        if ($usuarioId === auth()->id()) {
            return false;
        }
        
        // No puede dar de baja al admin principal (ID=1)
        if ($usuarioId === 1) {
            return false;
        }
        
        $usuario = User::find($usuarioId);
        if (!$usuario) {
            return false;
        }
        
        // Si es administrador, verificar que no sea el único
        $rolAdministrador = Rol::where('nombre', 'Administrador')->first();
        
        if ($rolAdministrador && $usuario->role_id === $rolAdministrador->id) {
            $totalAdministradores = User::where('role_id', $rolAdministrador->id)
                                         ->where('activo', 1)
                                         ->count();
            
            if ($totalAdministradores <= 1) {
                return false;
            }
        }
        
        return true;
    }

    // Cerrar modal y limpiar
    public function resetFormulario()
    {
        $this->reset([
            'nombre', 'email', 'password', 'role_id', 'showModal',
            'usuarioSeleccionado', 'modoEdicion'
        ]);
    }
}