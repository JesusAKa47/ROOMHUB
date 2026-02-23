import 'package:flutter/foundation.dart';

import 'package:roomhub/config/api_config.dart';
import 'package:roomhub/models/user_model.dart';
import 'package:roomhub/services/roomhub_api.dart';
import 'package:roomhub/storage/token_storage.dart';

/// Provider de autenticación conectado a la API de RoomHub.
/// Mantiene isLoggedIn, userName, role y user para compatibilidad con tu UI.
class AuthProvider extends ChangeNotifier {
  AuthProvider() {
    _api = RoomHubApi(
      baseUrl: roomHubApiBaseUrl,
      getToken: TokenStorage.getToken,
      onTokenCleared: () {
        _user = null;
        TokenStorage.clearToken();
        notifyListeners();
      },
    );
  }

  late final RoomHubApi _api;

  UserModel? _user;
  bool _isLoading = false;
  String? _error;

  UserModel? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;

  bool get isLoggedIn => _user != null && (TokenStorage.getToken()?.isNotEmpty ?? false);
  String get userName => _user?.name ?? 'Usuario RoomHub';
  String get role => _user != null
      ? (_user!.isOwner ? 'anfitrion' : 'cliente')
      : 'cliente';

  /// Registro: name, email, password, passwordConfirmation (llamada a la API).
  /// Tras éxito guarda token y usuario e inicia sesión.
  Future<bool> register(String name, String email, String password, String passwordConfirmation) async {
    _error = null;
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _api.register(
        name: name,
        email: email,
        password: password,
        passwordConfirmation: passwordConfirmation,
      );
      await TokenStorage.saveToken(response.token);
      _user = response.user;
      _isLoading = false;
      notifyListeners();
      return true;
    } on ApiException catch (e) {
      _error = e.message;
      _isLoading = false;
      notifyListeners();
      return false;
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  /// Iniciar sesión con email y contraseña (llamada a la API).
  Future<bool> login(String email, String password) async {
    _error = null;
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _api.login(email: email, password: password);
      await TokenStorage.saveToken(response.token);
      _user = response.user;
      _isLoading = false;
      notifyListeners();
      return true;
    } on ApiException catch (e) {
      _error = e.message;
      _isLoading = false;
      notifyListeners();
      return false;
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  /// Cerrar sesión (revoca el token en el servidor).
  Future<void> logout() async {
    _error = null;
    _isLoading = true;
    notifyListeners();
    try {
      await _api.logout();
    } catch (_) {}
    _user = null;
    await TokenStorage.clearToken();
    _isLoading = false;
    notifyListeners();
  }

  /// Cargar usuario actual si hay token guardado (llamar al abrir la app).
  Future<bool> loadUser() async {
    if (TokenStorage.getToken() == null || TokenStorage.getToken()!.isEmpty) {
      return false;
    }
    _error = null;
    _isLoading = true;
    notifyListeners();
    try {
      _user = await _api.getUser();
      _isLoading = false;
      notifyListeners();
      return true;
    } on ApiException catch (e) {
      _error = e.message;
      _user = null;
      await TokenStorage.clearToken();
      _isLoading = false;
      notifyListeners();
      return false;
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  void updateUserName(String newName) {
    if (_user != null) {
      _user = UserModel(
        id: _user!.id,
        name: newName,
        email: _user!.email,
        role: _user!.role,
        status: _user!.status,
        ownerId: _user!.ownerId,
        clientId: _user!.clientId,
        isAdmin: _user!.isAdmin,
        isOwner: _user!.isOwner,
        isClient: _user!.isClient,
      );
      notifyListeners();
    }
  }

  void toggleRole() {
    // El rol lo define la API (is_owner / is_client). Aquí no cambiamos nada real.
    notifyListeners();
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
