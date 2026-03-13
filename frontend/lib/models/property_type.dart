class PropertyType {
  final int? id;
  final String name;
  final String? description;

  PropertyType({this.id, required this.name, this.description});

  factory PropertyType.fromJson(Map<String, dynamic> json) {
    return PropertyType(
      id: json['id'],
      name: json['name'],
      description: json['description'],
    );
  }

  Map<String, dynamic> toJson() {
    return {'name': name, 'description': description};
  }
}
