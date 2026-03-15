class Room {
  final int? id;
  final int propertyId;
  final String roomNumber;
  final String? floor;
  final String status;

  Room({
    this.id,
    required this.propertyId,
    required this.roomNumber,
    this.floor,
    required this.status,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      propertyId: json['property_id'],
      roomNumber: json['room_number'],
      floor: json['floor'],
      status: json['status'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'property_id': propertyId,
      'room_number': roomNumber,
      'floor': floor,
      'status': status,
    };
  }
}
