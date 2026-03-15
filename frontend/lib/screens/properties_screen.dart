import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:frontend/models/property.dart';
import 'package:frontend/providers/property_provider.dart';
import 'package:frontend/screens/room_detail_screen.dart';

class PropertiesScreen extends StatefulWidget {
  @override
  _PropertiesScreenState createState() => _PropertiesScreenState();
}

class _PropertiesScreenState extends State<PropertiesScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final provider = Provider.of<PropertyProvider>(context, listen: false);
      provider.fetchProperties();
      provider.fetchPropertyTypes();
    });
  }

  void _showFormDialog(BuildContext context, [Property? property]) {
    final provider = Provider.of<PropertyProvider>(context, listen: false);
    final propertyTypes = provider.propertyTypes;

    final _nameController = TextEditingController(text: property?.name ?? '');
    final _descriptionController = TextEditingController(
      text: property?.description ?? '',
    );
    final _capacityController = TextEditingController(
      text: property?.capacity.toString() ?? '',
    );
    final _priceController = TextEditingController(
      text: property?.price?.toString() ?? '',
    );
    final _formKey = GlobalKey<FormState>();

    int? _selectedTypeId = property?.propertyTypeId ?? (propertyTypes.isNotEmpty ? propertyTypes.first['id'] : null);
    String _selectedCategory = property?.category ?? 'kamar_inap';
    String _selectedStatus = property?.status ?? 'active';

    showDialog(
      context: context,
      builder: (ctx) {
        return StatefulBuilder(
          builder: (context, setState) {
            return AlertDialog(
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16),
              ),
              title: Text(
                property == null ? 'Add Property' : 'Edit Property',
                style: GoogleFonts.poppins(fontWeight: FontWeight.w600),
              ),
              content: Form(
                key: _formKey,
                child: SingleChildScrollView(
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      DropdownButtonFormField<int>(
                        value: _selectedTypeId,
                        decoration: const InputDecoration(
                          labelText: 'Property Type',
                          prefixIcon: Icon(Icons.category_outlined),
                        ),
                        items: propertyTypes
                            .map(
                              (t) => DropdownMenuItem<int>(
                                value: t['id'],
                                child: Text(t['name']),
                              ),
                            )
                            .toList(),
                        onChanged: (val) =>
                            setState(() => _selectedTypeId = val),
                        validator: (value) =>
                            value == null ? 'Required' : null,
                      ),
                      const SizedBox(height: 16),
                      DropdownButtonFormField<String>(
                        value: _selectedCategory,
                        decoration: const InputDecoration(
                          labelText: 'Category',
                          prefixIcon: Icon(Icons.label_outline),
                        ),
                        items: const [
                          DropdownMenuItem(value: 'kamar_inap', child: Text('Kamar Inap')),
                          DropdownMenuItem(value: 'ruang_rapat', child: Text('Ruang Rapat')),
                          DropdownMenuItem(value: 'kelas', child: Text('Kelas')),
                          DropdownMenuItem(value: 'lainnya', child: Text('Lainnya')),
                        ],
                        onChanged: (val) =>
                            setState(() => _selectedCategory = val!),
                        validator: (value) =>
                            value == null ? 'Required' : null,
                      ),
                      const SizedBox(height: 16),
                      TextFormField(
                        controller: _nameController,
                        decoration: const InputDecoration(
                          labelText: 'Property Name',
                          prefixIcon: Icon(Icons.home_work_outlined),
                        ),
                        validator: (value) =>
                            value == null || value.isEmpty ? 'Required' : null,
                      ),
                      const SizedBox(height: 16),
                      TextFormField(
                        controller: _descriptionController,
                        decoration: const InputDecoration(
                          labelText: 'Description (Optional)',
                          prefixIcon: Icon(Icons.notes),
                        ),
                        maxLines: 2,
                      ),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: TextFormField(
                              controller: _capacityController,
                              decoration: const InputDecoration(
                                labelText: 'Capacity',
                                prefixIcon: Icon(Icons.groups_outlined),
                                suffixText: 'Pax',
                              ),
                              keyboardType: TextInputType.number,
                              validator: (value) {
                                if (value == null || value.isEmpty)
                                  return 'Required';
                                if (int.tryParse(value) == null)
                                  return 'Number';
                                return null;
                              },
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: TextFormField(
                              controller: _priceController,
                              decoration: const InputDecoration(
                                labelText: 'Price',
                                prefixIcon: Icon(Icons.payments_outlined),
                              ),
                              keyboardType: TextInputType.number,
                              validator: (value) {
                                if (value == null || value.isEmpty)
                                  return 'Required';
                                if (double.tryParse(value) == null)
                                  return 'Number';
                                return null;
                              },
                            ),
                          ),
                        ],
                      ),
                      const SizedBox(height: 16),
                      DropdownButtonFormField<String>(
                        value: _selectedStatus,
                        decoration: const InputDecoration(
                          labelText: 'Status',
                          prefixIcon: Icon(Icons.info_outline),
                        ),
                        items: ['active', 'inactive']
                            .map(
                              (s) => DropdownMenuItem(
                                value: s,
                                child: Text(s.toUpperCase()),
                              ),
                            )
                            .toList(),
                        onChanged: (val) =>
                            setState(() => _selectedStatus = val!),
                      ),
                    ],
                  ),
                ),
              ),
              actionsPadding: const EdgeInsets.symmetric(
                horizontal: 24,
                vertical: 16,
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.of(ctx).pop(),
                  child: const Text('Cancel'),
                ),
                ElevatedButton(
                  onPressed: () async {
                    if (_formKey.currentState!.validate()) {
                      final newProperty = Property(
                        id: property?.id,
                        propertyTypeId: _selectedTypeId,
                        category: _selectedCategory,
                        name: _nameController.text,
                        description: _descriptionController.text,
                        capacity: int.parse(_capacityController.text),
                        price: _priceController.text,
                        status: _selectedStatus,
                      );

                      bool success;
                      if (property == null) {
                        success = await provider.createProperty(newProperty);
                      } else {
                        success = await provider.updateProperty(
                          property.id!,
                          newProperty,
                        );
                      }

                      Navigator.of(ctx).pop();
                      if (success && mounted) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text('Property Saved')),
                        );
                      }
                    }
                  },
                  child: const Text('Save'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  Widget _buildStatusBadge(String status) {
    Color bg;
    Color textColors;
    String label = status.toUpperCase();

    if (status == 'available') {
      bg = const Color(0xFFDCFCE7); // green-100
      textColors = const Color(0xFF166534); // green-800
    } else if (status == 'occupied') {
      bg = const Color(0xFFFEE2E2); // red-100
      textColors = const Color(0xFF991B1B); // red-800
    } else {
      bg = const Color(0xFFFFEDD5); // orange-100
      textColors = const Color(0xFF9A3412); // orange-800
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        label,
        style: GoogleFonts.inter(
          fontSize: 12,
          fontWeight: FontWeight.bold,
          color: textColors,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Consumer<PropertyProvider>(
        builder: (ctx, provider, _) {
          if (provider.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }
          if (provider.properties.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.meeting_room_outlined,
                    size: 80,
                    color: Colors.blue.withOpacity(0.3),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'No properties currently exist.',
                    style: GoogleFonts.inter(
                      fontSize: 18,
                      color: Colors.grey.shade600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Manage your properties here.',
                    style: GoogleFonts.inter(color: Colors.grey.shade500),
                  ),
                ],
              ).animate().fadeIn(duration: 500.ms).slideY(begin: 0.1),
            );
          }
          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: provider.properties.length,
            itemBuilder: (ctx, index) {
              final property = provider.properties[index];
              return InkWell(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => RoomDetailScreen(property: property),
                    ),
                  ).then((_) {
                    if (mounted) {
                      Provider.of<PropertyProvider>(context, listen: false).fetchProperties();
                    }
                  });
                },
                child: Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Container(
                          height: 80,
                          width: 80,
                          decoration: BoxDecoration(
                            color: const Color(0xFFEFF6FF),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Stack(
                            alignment: Alignment.center,
                            children: [
                              const Icon(
                                Icons.home_work_outlined,
                                size: 40,
                                color: Color(0xFF3B82F6),
                              ),
                              Positioned(
                                bottom: 4,
                                child: Text(
                                  property.category?.replaceAll('_', ' ').toUpperCase() ?? '',
                                  style: const TextStyle(fontSize: 8, fontWeight: FontWeight.bold),
                                ),
                              ),
                            ],
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                children: [
                                  Expanded(
                                    child: Text(
                                      property.name,
                                      style: GoogleFonts.poppins(
                                        fontSize: 18,
                                        fontWeight: FontWeight.bold,
                                        color: const Color(0xFF1E293B),
                                      ),
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ),
                                  _buildStatusBadge(property.status),
                                ],
                              ),
                              Text(
                                property.propertyType ?? 'No Type',
                                style: GoogleFonts.inter(
                                  fontSize: 12,
                                  color: Colors.blue.shade700,
                                  fontWeight: FontWeight.w600,
                                ),
                              ),
                              const SizedBox(height: 8),
                              Wrap(
                                spacing: 16,
                                runSpacing: 8,
                                children: [
                                  Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      const Icon(Icons.groups, size: 16, color: Colors.grey),
                                      const SizedBox(width: 4),
                                      Text(
                                        '${property.capacity} Pax',
                                        style: GoogleFonts.inter(fontSize: 13, color: Colors.grey.shade700),
                                      ),
                                    ],
                                  ),
                                  Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      const Icon(Icons.meeting_room, size: 16, color: Colors.blueGrey),
                                      const SizedBox(width: 4),
                                      Text(
                                        'Rooms: ${property.availableRooms ?? 0}/${property.totalRooms ?? 0}',
                                        style: GoogleFonts.inter(
                                          fontSize: 13, 
                                          color: Colors.blueGrey.shade700,
                                          fontWeight: FontWeight.w500,
                                        ),
                                      ),
                                    ],
                                  ),
                                  Row(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      const Icon(Icons.payments, size: 16, color: Colors.green),
                                      const SizedBox(width: 4),
                                      Text(
                                        'Rp ${property.price ?? '0'}',
                                        style: GoogleFonts.inter(
                                          fontSize: 13,
                                          color: Colors.green.shade700,
                                          fontWeight: FontWeight.bold,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ],
                          ),
                        ),
                        Column(
                          children: [
                            IconButton(
                              icon: const Icon(
                                Icons.edit_outlined,
                                color: Colors.blueAccent,
                              ),
                              onPressed: () => _showFormDialog(context, property),
                              tooltip: 'Edit Property',
                            ),
                            IconButton(
                              icon: const Icon(
                                Icons.delete_outline,
                                color: Colors.redAccent,
                              ),
                              onPressed: () async {
                                final confirm = await showDialog<bool>(
                                  context: context,
                                  builder: (ctx) => AlertDialog(
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(16),
                                    ),
                                    title: const Text('Confirm Deletion'),
                                    content: Text(
                                      'Permanently delete ${property.name}?',
                                    ),
                                    actions: [
                                      TextButton(
                                        onPressed: () =>
                                            Navigator.of(ctx).pop(false),
                                        child: const Text('Cancel'),
                                      ),
                                      ElevatedButton(
                                        onPressed: () =>
                                            Navigator.of(ctx).pop(true),
                                        style: ElevatedButton.styleFrom(
                                          backgroundColor: Colors.red.shade600,
                                        ),
                                        child: const Text('Delete'),
                                      ),
                                    ],
                                  ),
                                );
                                if (confirm == true) {
                                  await provider.deleteProperty(property.id!);
                                }
                              },
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                ),
              ).animate().fadeIn(delay: (40 * index).ms).slideX(begin: 0.05);
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showFormDialog(context),
        icon: const Icon(Icons.add, color: Colors.white),
        label: Text(
          'Add Property',
          style: GoogleFonts.inter(
            color: Colors.white,
            fontWeight: FontWeight.w600,
          ),
        ),
        backgroundColor: const Color(0xFF1E3A8A),
        elevation: 4,
      ).animate().scale(delay: 400.ms, curve: Curves.easeOutBack),
    );
  }
}
