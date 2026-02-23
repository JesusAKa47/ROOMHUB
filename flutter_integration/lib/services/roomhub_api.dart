import 'dart:convert';

import 'package:http/http.dart' as http;

import '../models/user_model.dart';

/// Cliente para la API de RoomHub (login, logout, user).
/// Añade en pubspec.yaml: http: ^1.2.0
/// Guarda el token con shared_preferences o flutter_secure_storage.
class RoomHubApi {
  RoomHubApi({required this.baseUrl, this.getToken, this.onTokenCleared});

  /// URL base: http://127.0.0.1:8000 o https://tu-dominio.com
  final String baseUrl;

  /// Devuelve el token guardado (SharedPreferences, etc.)
  final String? Function()? getToken;

  /// Llamado cuando el servidor devuelve 401 para que borres el token
  final VoidCallback? onTokenCleared;

  String get _apiUrl => '$baseUrl/api';

  Map<String, String> _headers({bool withAuth = false}) {
    final h = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (withAuth) {
      final token = getToken?.call();
      if (token != null && token.isNotEmpty) {
        h['Authorization'] = 'Bearer $token';
      }
    }
    return h;
  }

  /// POST /api/register
  /// name, email, password, password_confirmation → token + user.
  Future<LoginResponse> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String deviceName = 'flutter-app',
  }) async {
    final res = await http.post(
      Uri.parse('$_apiUrl/register'),
      headers: _headers(),
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
        'device_name': deviceName,
      }),
    );

    final data = _decode(res.body);

    if (res.statusCode == 201 || res.statusCode == 200) {
      return LoginResponse(
        token: data['token'] as String,
        tokenType: data['token_type'] as String? ?? 'Bearer',
        expiresIn: data['expires_in'] as int? ?? 2592000,
        user: UserModel.fromJson(data['user'] as Map<String, dynamic>),
      );
    }

    if (res.statusCode == 422) {
      final errors = data['errors'] as Map<String, dynamic>?;
      if (errors != null && errors.isNotEmpty) {
        final first = errors.values.first;
        final msg = first is List ? (first.isNotEmpty ? first.first.toString() : null) : first.toString();
        throw ApiException(msg ?? 'Error de validación.');
      }
    }
    throw ApiException(data['message'] as String? ?? 'Error al registrarse.');
  }

  /// POST /api/login
  /// Devuelve el token y el usuario. Lanza en caso de error.
  Future<LoginResponse> login({
    required String email,
    required String password,
    String deviceName = 'flutter-app',
  }) async {
    final res = await http.post(
      Uri.parse('$_apiUrl/login'),
      headers: _headers(),
      body: jsonEncode({
        'email': email,
        'password': password,
        'device_name': deviceName,
      }),
    );

    final data = _decode(res.body);

    if (res.statusCode == 200) {
      return LoginResponse(
        token: data['token'] as String,
        tokenType: data['token_type'] as String? ?? 'Bearer',
        expiresIn: data['expires_in'] as int? ?? 2592000,
        user: UserModel.fromJson(data['user'] as Map<String, dynamic>),
      );
    }

    if (res.statusCode == 401) {
      throw ApiException(data['message'] as String? ?? 'Credenciales incorrectas.');
    }
    if (res.statusCode == 403) {
      throw ApiException(data['message'] as String? ?? 'Cuenta suspendida.');
    }
    if (res.statusCode == 429) {
      throw ApiException(data['message'] as String? ?? 'Demasiados intentos.');
    }
    throw ApiException(data['message'] as String? ?? 'Error de conexión.');
  }

  /// POST /api/logout — revoca el token actual
  Future<void> logout() async {
    final res = await http.post(
      Uri.parse('$_apiUrl/logout'),
      headers: _headers(withAuth: true),
    );
    if (res.statusCode == 401) {
      onTokenCleared?.call();
      return;
    }
    if (res.statusCode != 200) {
      final data = _decode(res.body);
      throw ApiException(data['message'] as String? ?? 'Error al cerrar sesión.');
    }
  }

  /// GET /api/user — usuario autenticado
  Future<UserModel> getUser() async {
    final res = await http.get(
      Uri.parse('$_apiUrl/user'),
      headers: _headers(withAuth: true),
    );

    if (res.statusCode == 401) {
      onTokenCleared?.call();
      throw ApiException('Sesión expirada. Vuelve a iniciar sesión.');
    }

    final data = _decode(res.body);
    if (res.statusCode != 200) {
      throw ApiException(data['message'] as String? ?? 'Error al obtener usuario.');
    }

    final userJson = data['user'] as Map<String, dynamic>?;
    if (userJson == null) throw ApiException('Respuesta inválida.');
    return UserModel.fromJson(userJson);
  }

  static dynamic _decode(String body) {
    try {
      return jsonDecode(body) as Map<String, dynamic>;
    } catch (_) {
      return <String, dynamic>{};
    }
  }
}

class LoginResponse {
  LoginResponse({
    required this.token,
    required this.tokenType,
    required this.expiresIn,
    required this.user,
  });
  final String token;
  final String tokenType;
  final int expiresIn;
  final UserModel user;
}

class ApiException implements Exception {
  ApiException(this.message);
  final String message;
  @override
  String toString() => message;
}

typedef VoidCallback = void Function();
