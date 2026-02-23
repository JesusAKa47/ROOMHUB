/// Modelo del usuario que devuelve la API de RoomHub.
class UserModel {
  final int id;
  final String name;
  final String email;
  final String role;
  final String status;
  final int? ownerId;
  final int? clientId;
  final bool isAdmin;
  final bool isOwner;
  final bool isClient;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    required this.role,
    required this.status,
    this.ownerId,
    this.clientId,
    required this.isAdmin,
    required this.isOwner,
    required this.isClient,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      role: json['role'] as String? ?? 'client',
      status: json['status'] as String? ?? 'active',
      ownerId: json['owner_id'] as int?,
      clientId: json['client_id'] as int?,
      isAdmin: json['is_admin'] as bool? ?? false,
      isOwner: json['is_owner'] as bool? ?? false,
      isClient: json['is_client'] as bool? ?? false,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'name': name,
        'email': email,
        'role': role,
        'status': status,
        'owner_id': ownerId,
        'client_id': clientId,
        'is_admin': isAdmin,
        'is_owner': isOwner,
        'is_client': isClient,
      };
}
