class Property {
  final int? id;
  final String name;
  final String? description;
  final int capacity;
  final String status;

  Property({
    this.id,
    required this.name,
    this.description,
    required this.capacity,
    required this.status,
  });

  factory Property.fromJson(Map<String, dynamic> json) {
    return Property(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      capacity: json['capacity'],
      status: json['status'] ?? 'available',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'name': name,
      'description': description,
      'capacity': capacity,
      'status': status,
    };
  }
}
