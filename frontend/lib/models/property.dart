import 'package:frontend/models/room.dart';

class Property {
  final int? id;
  final int? propertyTypeId;
  final String? category;
  final String name;
  final String? description;
  final int capacity;
  final String? price;
  final String status;
  final List<String>? images;

  final int? totalRooms;
  final int? availableRooms;
  final String? propertyType;
  final List<Room>? rooms;

  Property({
    this.id,
    this.propertyTypeId,
    this.propertyType,
    this.category,
    required this.name,
    this.description,
    required this.capacity,
    this.price,
    required this.status,
    this.images,
    this.rooms,
    this.totalRooms,
    this.availableRooms,
  });

  factory Property.fromJson(Map<String, dynamic> json) {
    return Property(
      id: json['id'],
      propertyTypeId: json['property_type_id'],
      propertyType: json['property_type'] != null ? json['property_type']['name'] : null,
      category: json['category'],
      name: json['name'],
      description: json['description'],
      capacity: json['capacity'],
      price: json['price']?.toString(),
      status: json['status'] ?? 'active',
      images: json['image'] != null ? List<String>.from(json['image']) : null,
      rooms: json['rooms'] != null
          ? (json['rooms'] as List).map((i) => Room.fromJson(i)).toList()
          : null,
      totalRooms: json['total_rooms'],
      availableRooms: json['available_rooms'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'property_type_id': propertyTypeId,
      'category': category,
      'name': name,
      'description': description,
      'capacity': capacity,
      'price': price,
      'status': status,
      'image': images,
    };
  }
}
