import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:frontend/models/booking.dart';
import 'package:frontend/providers/booking_provider.dart';
import 'package:frontend/providers/property_provider.dart';
import 'package:frontend/providers/room_provider.dart';

class BookingsScreen extends StatefulWidget {
  @override
  _BookingsScreenState createState() => _BookingsScreenState();
}

class _BookingsScreenState extends State<BookingsScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<PropertyProvider>(context, listen: false).fetchProperties();
      Provider.of<BookingProvider>(context, listen: false).fetchBookings();
    });
  }

  void _showFormDialog(BuildContext context, [Booking? booking]) {
    final properties = Provider.of<PropertyProvider>(
      context,
      listen: false,
    ).properties;
    if (properties.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: const Text('Required: Create a Property Room First'),
          backgroundColor: Colors.orange.shade800,
          behavior: SnackBarBehavior.floating,
        ),
      );
      return;
    }

    final _nameController = TextEditingController(
      text: booking?.contactName ?? '',
    );
    final _emailController = TextEditingController(
      text: booking?.contactEmail ?? '',
    );
    final _phoneController = TextEditingController(
      text: booking?.contactPhone ?? '',
    );
    final _institutionController = TextEditingController(
      text: booking?.institution ?? '',
    );
    final _formKey = GlobalKey<FormState>();

    int? _selectedPropertyId = booking?.propertyId ?? properties.first.id;
    DateTime _startDate = booking?.startDate ?? DateTime.now();
    DateTime _endDate =
        booking?.endDate ?? DateTime.now().add(const Duration(hours: 2));

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
                booking == null ? 'Schedule Booking' : 'Modify Booking',
                style: GoogleFonts.poppins(fontWeight: FontWeight.w600),
              ),
              content: Form(
                key: _formKey,
                child: SizedBox(
                  width: 500, // Make booking form wider for aesthetics
                  child: SingleChildScrollView(
                    child: Column(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        DropdownButtonFormField<int>(
                          value: _selectedPropertyId,
                          decoration: const InputDecoration(
                            labelText: 'Select Room/Property',
                            prefixIcon: Icon(Icons.meeting_room_outlined),
                          ),
                          items: properties
                              .map(
                                (p) => DropdownMenuItem(
                                  value: p.id,
                                  child: Text('${p.name} (${p.status})'),
                                ),
                              )
                              .toList(),
                          onChanged: (val) =>
                              setState(() => _selectedPropertyId = val),
                          validator: (value) =>
                              value == null ? 'Required' : null,
                        ),
                        const SizedBox(height: 16),
                        Row(
                          children: [
                            Expanded(
                              child: TextFormField(
                                controller: _nameController,
                                decoration: const InputDecoration(
                                  labelText: 'Contact Person',
                                  prefixIcon: Icon(Icons.person_outline),
                                ),
                                validator: (value) =>
                                    value == null || value.isEmpty
                                    ? 'Required'
                                    : null,
                              ),
                            ),
                            const SizedBox(width: 16),
                            Expanded(
                              child: TextFormField(
                                controller: _phoneController,
                                decoration: const InputDecoration(
                                  labelText: 'Phone No.',
                                  prefixIcon: Icon(Icons.phone_outlined),
                                ),
                                validator: (value) =>
                                    value == null || value.isEmpty
                                    ? 'Required'
                                    : null,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 16),
                        TextFormField(
                          controller: _emailController,
                          decoration: const InputDecoration(
                            labelText: 'Email Address',
                            prefixIcon: Icon(Icons.email_outlined),
                          ),
                          validator: (value) =>
                              value == null ||
                                  value.isEmpty ||
                                  !value.contains('@')
                              ? 'Valid Email Required'
                              : null,
                        ),
                        const SizedBox(height: 16),
                        TextFormField(
                          controller: _institutionController,
                          decoration: const InputDecoration(
                            labelText: 'Institution (Optional)',
                            prefixIcon: Icon(Icons.corporate_fare_outlined),
                          ),
                        ),
                        const SizedBox(height: 24),
                        const Divider(),
                        Padding(
                          padding: const EdgeInsets.symmetric(vertical: 8.0),
                          child: Text(
                            'Schedule Timeline',
                            style: GoogleFonts.poppins(
                              fontWeight: FontWeight.w600,
                              color: Colors.grey.shade700,
                            ),
                          ),
                        ),
                        Container(
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey.shade300),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Column(
                            children: [
                              ListTile(
                                title: Text(
                                  'Start: ${DateFormat('EEE, MMM d • yyyy HH:mm').format(_startDate)}',
                                  style: GoogleFonts.inter(
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                                trailing: const Icon(
                                  Icons.calendar_month,
                                  color: Color(0xFF3B82F6),
                                ),
                                onTap: () async {
                                  final date = await showDatePicker(
                                    context: context,
                                    initialDate: _startDate,
                                    firstDate: DateTime.now(),
                                    lastDate: DateTime(2030),
                                  );
                                  if (date != null) {
                                    final time = await showTimePicker(
                                      context: context,
                                      initialTime: TimeOfDay.fromDateTime(
                                        _startDate,
                                      ),
                                    );
                                    if (time != null) {
                                      setState(() {
                                        _startDate = DateTime(
                                          date.year,
                                          date.month,
                                          date.day,
                                          time.hour,
                                          time.minute,
                                        );
                                        if (_endDate.isBefore(_startDate)) {
                                          _endDate = _startDate.add(
                                            const Duration(hours: 1),
                                          );
                                        }
                                      });
                                    }
                                  }
                                },
                              ),
                              const Divider(height: 1),
                              ListTile(
                                title: Text(
                                  'End: ${DateFormat('EEE, MMM d • yyyy HH:mm').format(_endDate)}',
                                  style: GoogleFonts.inter(
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                                trailing: const Icon(
                                  Icons.event_available,
                                  color: Color(0xFF16A34A),
                                ),
                                onTap: () async {
                                  final date = await showDatePicker(
                                    context: context,
                                    initialDate: _endDate,
                                    firstDate: _startDate,
                                    lastDate: DateTime(2030),
                                  );
                                  if (date != null) {
                                    final time = await showTimePicker(
                                      context: context,
                                      initialTime: TimeOfDay.fromDateTime(
                                        _endDate,
                                      ),
                                    );
                                    if (time != null) {
                                      setState(() {
                                        _endDate = DateTime(
                                          date.year,
                                          date.month,
                                          date.day,
                                          time.hour,
                                          time.minute,
                                        );
                                      });
                                    }
                                  }
                                },
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
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
                  child: const Text(
                    'Cancel',
                    style: TextStyle(color: Colors.grey),
                  ),
                ),
                ElevatedButton(
                  onPressed: () async {
                    if (_formKey.currentState!.validate()) {
                      if (_endDate.isBefore(_startDate)) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(
                            content: const Text(
                              'End Date must be after Start Date',
                            ),
                            backgroundColor: Colors.red.shade600,
                          ),
                        );
                        return;
                      }

                      final provider = Provider.of<BookingProvider>(
                        context,
                        listen: false,
                      );
                      final newBooking = Booking(
                        id: booking?.id,
                        propertyId: _selectedPropertyId!,
                        contactName: _nameController.text,
                        contactEmail: _emailController.text,
                        contactPhone: _phoneController.text,
                        institution: _institutionController.text,
                        startDate: _startDate,
                        endDate: _endDate,
                        status: 'scheduled',
                      );

                      bool success;
                      if (booking == null) {
                        success = await provider.createBooking(newBooking);
                      } else {
                        success = await provider.updateBooking(
                          booking.id!,
                          newBooking,
                        );
                      }

                      Navigator.of(ctx).pop();
                      if (success && mounted) {
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(
                            content: Text('Booking Scheduled Successfully'),
                          ),
                        );
                        // Refresh properties to see dynamic status change
                        Provider.of<PropertyProvider>(
                          context,
                          listen: false,
                        ).fetchProperties();
                      }
                    }
                  },
                  child: const Text('Save Schedule'),
                ),
              ],
            );
          },
        );
      },
    );
  }

  void _showCheckInDialog(BuildContext context, Booking booking) {
    if (booking.propertyId == null) return;

    final roomProvider = Provider.of<RoomProvider>(context, listen: false);
    roomProvider.fetchRooms(propertyId: booking.propertyId);

    int? _selectedRoomId;

    showDialog(
      context: context,
      builder: (ctx) {
        return AlertDialog(
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          title: Text(
            'Check-In: Assign Room',
            style: GoogleFonts.poppins(fontWeight: FontWeight.w600),
          ),
          content: Consumer<RoomProvider>(
            builder: (context, provider, child) {
              if (provider.isLoading) {
                return const SizedBox(
                  height: 100,
                  child: Center(child: CircularProgressIndicator()),
                );
              }

              final availableRooms = provider.rooms
                  .where((r) => r.status == 'available')
                  .toList();

              if (availableRooms.isEmpty) {
                return const Text(
                  'No available rooms for this property. Please update room status or clean available rooms first.',
                  style: TextStyle(color: Colors.red),
                );
              }

              return Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text('Select an available room to assign to this booking:'),
                  const SizedBox(height: 16),
                  DropdownButtonFormField<int>(
                    decoration: const InputDecoration(
                      labelText: 'Room Number',
                      prefixIcon: Icon(Icons.door_front_door_outlined),
                    ),
                    items: availableRooms
                        .map((r) => DropdownMenuItem(
                              value: r.id,
                              child: Text('Room ${r.roomNumber} (${r.floor ?? 'Floor ?'})'),
                            ))
                        .toList(),
                    onChanged: (val) => _selectedRoomId = val,
                  ),
                ],
              );
            },
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(ctx),
              child: const Text('Cancel'),
            ),
            ElevatedButton(
              onPressed: () async {
                if (_selectedRoomId != null) {
                  final success = await Provider.of<BookingProvider>(
                    context,
                    listen: false,
                  ).assignRoom(booking.id!, _selectedRoomId!);

                  Navigator.pop(ctx);
                  if (success && mounted) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Checked-In Successfully')),
                    );
                    Provider.of<PropertyProvider>(context, listen: false)
                        .fetchProperties();
                  }
                }
              },
              child: const Text('Confirm Check-In'),
            ),
          ],
        );
      },
    );
  }

  Widget _buildStatusBadge(String status) {
    Color bg;
    Color textColors;

    switch (status) {
      case 'scheduled':
        bg = const Color(0xFFDBEAFE); // blue-100
        textColors = const Color(0xFF1E40AF); // blue-800
        break;
      case 'in_use':
        bg = const Color(0xFFDCFCE7); // green-100
        textColors = const Color(0xFF166534); // green-800
        break;
      case 'finished':
        bg = const Color(0xFFF3F4F6); // gray-100
        textColors = const Color(0xFF374151); // gray-800
        break;
      case 'cancelled':
        bg = const Color(0xFFFEE2E2); // red-100
        textColors = const Color(0xFF991B1B); // red-800
        break;
      default:
        bg = const Color(0xFFE2E8F0); // slate-200
        textColors = const Color(0xFF475569); // slate-700
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(12),
      ),
      child: Text(
        status.toUpperCase().replaceAll('_', ' '),
        style: GoogleFonts.inter(
          fontSize: 11,
          fontWeight: FontWeight.bold,
          color: textColors,
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Consumer<BookingProvider>(
        builder: (ctx, provider, _) {
          if (provider.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }
          if (provider.bookings.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.event_note_outlined,
                    size: 80,
                    color: Colors.blue.withOpacity(0.3),
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'No bookings found.',
                    style: GoogleFonts.inter(
                      fontSize: 18,
                      color: Colors.grey.shade600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Schedule meetings and room reservations here.',
                    style: GoogleFonts.inter(color: Colors.grey.shade500),
                  ),
                ],
              ).animate().fadeIn(duration: 500.ms).slideY(begin: 0.1),
            );
          }
          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: provider.bookings.length,
            itemBuilder: (ctx, index) {
              final booking = provider.bookings[index];
              final isCancelled = booking.status == 'cancelled';

              return Card(
                color: isCancelled ? const Color(0xFFFAFAFA) : Colors.white,
                margin: const EdgeInsets.only(bottom: 12),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    children: [
                      Row(
                        children: [
                          Container(
                            height: 48,
                            width: 48,
                            decoration: BoxDecoration(
                              color: isCancelled
                                  ? Colors.grey.shade200
                                  : const Color(0xFFEFF6FF),
                              borderRadius: BorderRadius.circular(10),
                            ),
                            child: Icon(
                              Icons.bookmark_outline,
                              size: 24,
                              color: isCancelled
                                  ? Colors.grey
                                  : const Color(0xFF3B82F6),
                            ),
                          ),
                          const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Expanded(
                                      child: Text(
                                        booking.property?.name ??
                                            "Unknown Property",
                                        style: GoogleFonts.poppins(
                                          fontSize: 16,
                                          fontWeight: FontWeight.bold,
                                          decoration: isCancelled
                                              ? TextDecoration.lineThrough
                                              : null,
                                          color: isCancelled
                                              ? Colors.grey
                                              : const Color(0xFF1E293B),
                                        ),
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                    ),
                                    _buildStatusBadge(booking.status),
                                  ],
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  '${booking.contactName} • ${booking.institution ?? 'Individual'}',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    color: Colors.grey.shade700,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ],
                      ),
                      const Padding(
                        padding: EdgeInsets.symmetric(vertical: 12.0),
                        child: Divider(height: 1),
                      ),
                      Row(
                        children: [
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  'Reservation Window',
                                  style: GoogleFonts.inter(
                                    fontSize: 12,
                                    fontWeight: FontWeight.normal,
                                    color: Colors.grey.shade500,
                                  ),
                                ),
                                const SizedBox(height: 4),
                                Text(
                                  '${DateFormat('MMM d').format(booking.startDate)}, ${DateFormat('HH:mm').format(booking.startDate)} - ${DateFormat('HH:mm').format(booking.endDate)}',
                                  style: GoogleFonts.inter(
                                    fontSize: 14,
                                    fontWeight: FontWeight.w600,
                                    color: const Color(0xFF334155),
                                  ),
                                ),
                              ],
                            ),
                          ),
                          Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              if (booking.status == 'scheduled')
                                IconButton(
                                  icon: const Icon(
                                    Icons.login_outlined,
                                    color: Colors.green,
                                  ),
                                  tooltip: 'Check-In (Assign Room)',
                                  onPressed: () => 
                                      _showCheckInDialog(context, booking),
                                ),
                              if (booking.status == 'in_use')
                                IconButton(
                                  icon: const Icon(
                                    Icons.logout_outlined,
                                    color: Colors.blueGrey,
                                  ),
                                  tooltip: 'Check-Out (Finish)',
                                  onPressed: () async {
                                    final confirm = await showDialog<bool>(
                                      context: context,
                                      builder: (ctx) => AlertDialog(
                                        title: const Text('Confirm Check-Out'),
                                        content: const Text('Finish this booking and free the room?'),
                                        actions: [
                                          TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('No')),
                                          ElevatedButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Yes, Checkout')),
                                        ],
                                      ),
                                    );
                                    if (confirm == true) {
                                      await provider.checkoutBooking(booking.id!);
                                      Provider.of<PropertyProvider>(context, listen: false).fetchProperties();
                                    }
                                  },
                                ),
                              if (booking.status == 'scheduled' || booking.status == 'in_use')
                                IconButton(
                                  icon: const Icon(
                                    Icons.edit_outlined,
                                    color: Colors.blueAccent,
                                  ),
                                  tooltip: 'Edit Details',
                                  onPressed: () =>
                                      _showFormDialog(context, booking),
                                ),
                              if (booking.status == 'scheduled')
                                IconButton(
                                  icon: const Icon(
                                    Icons.event_busy,
                                    color: Colors.orange,
                                  ),
                                  tooltip: 'Cancel Booking',
                                  onPressed: () async {
                                    final confirm = await showDialog<bool>(
                                      context: context,
                                      builder: (ctx) => AlertDialog(
                                        shape: RoundedRectangleBorder(
                                          borderRadius: BorderRadius.circular(
                                            16,
                                          ),
                                        ),
                                        title: const Text('Cancel Booking?'),
                                        content: const Text(
                                          'Mark booking as cancelled? Room will become available.',
                                        ),
                                        actions: [
                                          TextButton(
                                            onPressed: () =>
                                                Navigator.of(ctx).pop(false),
                                            child: const Text('No'),
                                          ),
                                          ElevatedButton(
                                            onPressed: () =>
                                                Navigator.of(ctx).pop(true),
                                            style: ElevatedButton.styleFrom(
                                              backgroundColor: Colors.orange,
                                            ),
                                            child: const Text('Yes, Cancel'),
                                          ),
                                        ],
                                      ),
                                    );
                                    if (confirm == true) {
                                      await provider.cancelBooking(booking.id!);
                                      Provider.of<PropertyProvider>(
                                        context,
                                        listen: false,
                                      ).fetchProperties();
                                    }
                                  },
                                ),
                              IconButton(
                                icon: const Icon(
                                  Icons.delete_outline,
                                  color: Colors.redAccent,
                                ),
                                tooltip: 'Delete Permanently',
                                onPressed: () async {
                                  final confirm = await showDialog<bool>(
                                    context: context,
                                    builder: (ctx) => AlertDialog(
                                      shape: RoundedRectangleBorder(
                                        borderRadius: BorderRadius.circular(16),
                                      ),
                                      title: const Text('Delete Permanent?'),
                                      content: const Text(
                                        'Wipe this booking from the system completely?',
                                      ),
                                      actions: [
                                        TextButton(
                                          onPressed: () =>
                                              Navigator.of(ctx).pop(false),
                                          child: const Text('No'),
                                        ),
                                        ElevatedButton(
                                          onPressed: () =>
                                              Navigator.of(ctx).pop(true),
                                          style: ElevatedButton.styleFrom(
                                            backgroundColor:
                                                Colors.red.shade600,
                                          ),
                                          child: const Text('Yes'),
                                        ),
                                      ],
                                    ),
                                  );
                                  if (confirm == true) {
                                    await provider.deleteBooking(booking.id!);
                                    Provider.of<PropertyProvider>(
                                      context,
                                      listen: false,
                                    ).fetchProperties();
                                  }
                                },
                              ),
                            ],
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ).animate().fadeIn(delay: (40 * index).ms).slideX(begin: -0.05);
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => _showFormDialog(context),
        icon: const Icon(Icons.add_task, color: Colors.white),
        label: Text(
          'New Booking',
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
