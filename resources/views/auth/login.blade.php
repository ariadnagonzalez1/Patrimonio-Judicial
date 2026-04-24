<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 
                bg-gradient-to-br from-gray-50 via-indigo-50 to-blue-100">

        <!-- Encabezado institucional -->
        <div class="text-center mb-8">
            <!-- Logo institucional -->
            <div class="flex justify-center mb-4">
                <div class="p-2 bg-white/70 border border-indigo-100 rounded-full shadow-sm">
                    <img src="{{ asset('images/logopoderjudicial.png') }}" 
                         alt="Logo Poder Judicial"
                         class="h-24 w-auto mx-auto drop-shadow-md">
                </div>
            </div>

            <!-- Nombre del sistema -->
            <h1 class="text-2xl font-bold text-gray-900">Sistema de Gestión Patrimonial</h1>
         
        </div>

        <!-- Card del formulario -->
       <div class="w-full max-w-md bg-blue-50 border border-blue-100 rounded-2xl shadow-lg 
     p-6 h-[420px] flex flex-col justify-between">
            <h2 class="text-xl font-semibold text-gray-800 mb-1">Iniciar Sesión</h2>
            <p class="text-sm text-gray-500 mb-6">Ingrese sus credenciales para acceder al sistema</p>

            <!-- Mensajes de sesión -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Correo -->
                <div class="mb-4">
                    <x-input-label for="email" value="Correo Electrónico"/>
                    <x-text-input id="email" type="email" name="email"
                    class="mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="usuario@judicial.gov"
                    :value="old('email')" required autofocus autocomplete="username"
                    style="background-color: white; border-color: #ccc;" />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div class="mb-6">
                    <x-input-label for="password" value="Contraseña"/>
                    <x-text-input id="password" type="password" name="password"
                    class="mt-1 w-full border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="********"
                    required autocomplete="current-password"
                    style="background-color: white; border-color: #ccc;" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Botón -->
                <div>
                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-900 text-white font-semibold rounded-lg hover:bg-indigo-800 transition-colors">
                        Ingresar
                    </button>
                </div>
            </form>

        
        <!-- Footer institucional -->
        <p class="mt-6 text-gray-500 text-xs">© {{ date('Y') }} Poder Judicial - Sistema Patrimonial</p>
    </div>
</x-guest-layout> 

