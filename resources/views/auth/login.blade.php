<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SchoolConnect</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#7A1625',    // Vino/Guinda
                        secondary: '#B78E4A',  // Oro/Dorado oscuro
                        light: '#FFFFFF',       // Blanco
                        background: '#EDEDED', // Gris claro/Fondo
                        'text-dark': '#000000', // Negro
                        'text-gray': '#4A4A4A', // Gris oscuro
                        success: '#2E7D32',     // Verde
                        warning: '#D84315',     // Naranja/rojo
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-primary {
            background-color: #7A1625; /* Guinda sólido */
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-focus:focus {
            border-color: #B78E4A;
            box-shadow: 0 0 0 3px rgba(183, 142, 74, 0.1);
        }
        
        .btn-primary {
            background-color: #B78E4A; /* Dorado sólido */
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #A67D3F; /* Dorado más oscuro en hover */
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(183, 142, 74, 0.3);
        }
        
        .logo-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .logo-container:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.2);
        }
        
        .text-secondary {
            color: #B78E4A;
        }
        
        .hover\:text-secondary:hover {
            color: #B78E4A;
        }
    </style>
</head>
<body class="min-h-screen bg-primary flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <!-- Logo y Título -->
        <div class="text-center mb-4">
            <a href="/" class="inline-block">
                <div class="logo-container inline-block mb-2">
                    <img src="assets/img/logo-completo-blanco.png" alt="SchoolConnect" class="h-20 mx-auto">
                </div>
            </a>
            <p class="text-white/80 text-xs">Red Social Estudiantil</p>
        </div>

        <!-- Formulario de Login -->
        <div class="glass-effect rounded-xl p-6 shadow-2xl">
            <div class="text-center mb-4">
                <h2 class="text-xl font-semibold text-white mb-1">Iniciar Sesión</h2>
                <p class="text-white/70 text-xs">Accede a tu cuenta</p>
            </div>

            @if ($errors->any())
                <div class="mb-3 p-3 bg-red-500/20 border border-red-500/30 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-400 mr-2 text-xs"></i>
                        <span class="text-red-200 text-xs">{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                
                <!-- Campo Email -->
                <div>
                    <label for="_id" class="block text-xs font-medium text-white/90 mb-1">
                        <i class="fas fa-envelope mr-1"></i>Correo Electrónico
                    </label>
                    <div class="relative">
                        <input 
                            id="_id" 
                            type="email" 
                            name="_id" 
                            value="{{ old('_id') }}" 
                            required 
                            autofocus
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 input-focus transition-all duration-300 text-sm"
                            placeholder="tu@email.com"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-envelope text-white/50 text-xs"></i>
                        </div>
                    </div>
                    @error('_id')
                        <p class="mt-1 text-red-300 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Contraseña -->
                <div>
                    <label for="password" class="block text-xs font-medium text-white/90 mb-1">
                        <i class="fas fa-lock mr-1"></i>Contraseña
                    </label>
                    <div class="relative">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-white/50 input-focus transition-all duration-300 text-sm"
                            placeholder="••••••••"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-lock text-white/50 text-xs"></i>
                        </div>
                    </div>
                    @error('password')
                        <p class="mt-1 text-red-300 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkbox Recordarme -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center text-white/80 text-xs">
                        <input type="checkbox" name="remember" class="mr-2 w-3 h-3 text-secondary bg-white/10 border-white/20 rounded focus:ring-secondary">
                        Recordarme
                    </label>
                </div>

                <!-- Botón de Login -->
                <button 
                    type="submit" 
                    class="w-full btn-primary text-white font-semibold py-3 px-6 rounded-lg text-base transition-all duration-300"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Ingresar
                </button>
            </form>
    
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-white/50 text-xs">
                Desarrollado por 
                <span class="text-secondary font-semibold">Corvus Byte</span>
            </p>
        </div>
    </div>

    <!-- Efectos de partículas de fondo -->
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-1 h-1 bg-white/20 rounded-full animate-pulse"></div>
        <div class="absolute top-1/3 right-1/4 w-0.5 h-0.5 bg-white/30 rounded-full animate-pulse delay-1000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1 h-1 bg-white/25 rounded-full animate-pulse delay-2000"></div>
        <div class="absolute bottom-1/3 right-1/3 w-0.5 h-0.5 bg-white/20 rounded-full animate-pulse delay-3000"></div>
    </div>
</body>
</html>
