import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:roomhub/providers/auth_provider.dart';
import 'package:roomhub/screens/login_screen.dart';
import 'package:roomhub/storage/token_storage.dart';

/// Muestra LoginScreen si no hay sesión; si hay token intenta cargar el usuario.
/// Cuando ya está logueado, muestra la pantalla principal (ajusta [homeWidget]).
class AuthGate extends StatefulWidget {
  const AuthGate({super.key});

  @override
  State<AuthGate> createState() => _AuthGateState();
}

class _AuthGateState extends State<AuthGate> {
  bool _initialized = false;

  @override
  void initState() {
    super.initState();
    _tryLoadUser();
  }

  Future<void> _tryLoadUser() async {
    final auth = context.read<AuthProvider>();
    if (TokenStorage.getToken() != null && TokenStorage.getToken()!.isNotEmpty) {
      await auth.loadUser();
    }
    if (!mounted) return;
    setState(() => _initialized = true);
  }

  @override
  Widget build(BuildContext context) {
    final auth = context.watch<AuthProvider>();

    if (!_initialized) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(),
        ),
      );
    }

    if (auth.isLoggedIn) {
      return homeWidget;
    }

    return const LoginScreen();
  }

  /// Sustituye por tu pantalla principal (HomeScreen, MainScreen, etc.).
  Widget get homeWidget {
    return Scaffold(
      appBar: AppBar(
        title: const Text('RoomHub'),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(
              'Hola, ${context.watch<AuthProvider>().userName}',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 16),
            FilledButton.icon(
              onPressed: () => context.read<AuthProvider>().logout(),
              icon: const Icon(Icons.logout),
              label: const Text('Cerrar sesión'),
            ),
          ],
        ),
      ),
    );
  }
}
