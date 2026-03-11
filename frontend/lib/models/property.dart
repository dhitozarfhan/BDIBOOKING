import 'package:frontend/models/property_type.dart';

class Property {
  final int? id;
  final int propertyTypeId;
  final String name;
  final String? description;
  final int capacity;
  final String status;
  final PropertyType? type;

  Property({
    this.id,
    required this.propertyTypeId,
    required this.name,
    this.description,
    required this.capacity,
    required this.status,
    this.type,
  });

  factory Property.fromJson(Map<String, dynamic> json) {
    return Property(
      id: json['id'],
      propertyTypeId: json['property_type_id'],
      name: json['name'],
      description: json['description'],
      capacity: json['capacity'],
      status: json['status'] ?? 'available',
      type: json['type'] != null ? PropertyType.fromJson(json['type']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'property_type_id': propertyTypeId,
      'name': name,
      'description': description,
      'capacity': capacity,
      'status': status,
    };
  }
}
