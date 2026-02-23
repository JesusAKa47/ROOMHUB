import 'package:flutter/foundation.dart';

import '../models/user_model.dart';
import '../services/roomhub_api.dart';

/// Provider de ejemplo que usa RoomHubApi.
/// Sustituye o integra con tu auth_provider.dart:
/// - login() llama a la API y guarda token + user
/// - logout() llama a la API y borra token
/// - getToken() / setToken() los implementas con SharedPreferences o flutter_secure_storage
class AuthProviderRoomHub extends ChangeNotifier {
  AuthProviderRoomHub({
    required RoomHubApi api,
    required void Function(String token) saveToken,
    required String? Function() getToken,
    required void Function() clearToken,
  })  : _api = api,
        _saveToken = saveToken,
        _getToken = getToken,
        _clearToken = clearToken {
    _api.getToken = getToken;
    _api.onTokenCleared = () {
      _user = null;
      clearToken();
      notifyListeners();
    };
  }

  final RoomHubApi _api;
  final void Function(String token) _saveToken;
  final String? Function() _getToken;
  final void Function() _clearToken;

  UserModel? _user;
  bool _isLoading = false;
  String? _error;

  UserModel? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isLoggedIn => _user != null && (_getToken()?.isNotEmpty ?? false);

  /// Login con email y contraseña.
  Future<bool> login(String email, String password) async {
    _error = null;
    _isLoading = true;
    notifyListeners();

    try {
      final response = await _api.login(email: email, password: password);
      _saveToken(response.token);
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

  /// Cerrar sesión (revoca token en el servidor).
  Future<void> logout() async {
    _error = null;
    _isLoading = true;
    notifyListeners();
    try {
      await _api.logout();
    } catch (_) {}
    _user = null;
    _clearToken();
    _isLoading = false;
    notifyListeners();
  }

  /// Cargar usuario actual (por ejemplo al abrir la app si ya hay token).
  Future<bool> loadUser() async {
    if (_getToken() == null || _getToken()!.isEmpty) return false;
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
      _clearToken();
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

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
