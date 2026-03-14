class Room {
  final int id;
  final String roomNumber;
  final String? floor;
  final String status;

  Room({
    required this.id,
    required this.roomNumber,
    this.floor,
    required this.status,
  });

  factory Room.fromJson(Map<String, dynamic> json) {
    return Room(
      id: json['id'],
      roomNumber: json['room_number'],
      floor: json['floor'],
      status: json['status'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'room_number': roomNumber,
      'floor': floor,
      'status': status,
    };
  }
}
