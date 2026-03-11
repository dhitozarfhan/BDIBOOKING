import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:flutter_animate/flutter_animate.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:frontend/providers/auth_provider.dart';
import 'package:frontend/screens/dashboard_screen.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _formKey = GlobalKey<FormState>();

  void _submit() async {
    if (_formKey.currentState!.validate()) {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      final error = await authProvider.login(
        _emailController.text,
        _passwordController.text,
      );

      if (error != null) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(error),
              backgroundColor: Colors.red.shade600,
              behavior: SnackBarBehavior.floating,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(10),
              ),
            ),
          );
        }
      } else {
        if (mounted) {
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(builder: (_) => DashboardScreen()),
          );
        }
      }
    }
  }

  // Builds the brand/hero section
  Widget _buildBrandSection({required bool isMobile}) {
    return Container(
      width: double.infinity,
      decoration: const BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [Color(0xFF1E3A8A), Color(0xFF3B82F6)],
        ),
      ),
      child: SafeArea(
        bottom: false,
        child: Padding(
          padding: EdgeInsets.symmetric(
            horizontal: isMobile ? 24.0 : 48.0,
            vertical: isMobile ? 32.0 : 48.0,
          ),
          child: Column(
            mainAxisAlignment:
                isMobile ? MainAxisAlignment.start : MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(
                Icons.meeting_room_rounded,
                size: isMobile ? 48 : 80,
                color: Colors.white,
              )
                  .animate()
                  .fadeIn(duration: 800.ms)
                  .slideY(begin: -0.2, end: 0, curve: Curves.easeOutCubic),
              SizedBox(height: isMobile ? 16 : 24),
              Text(
                'BDIRoom\nManagement Dashboard',
                style: GoogleFonts.poppins(
                  fontSize: isMobile ? 28 : 48,
                  height: 1.1,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ).animate(delay: 200.ms).fadeIn().slideX(begin: -0.1),
              SizedBox(height: isMobile ? 8 : 16),
              Text(
                'Effortlessly manage your properties, types, and bookings in one elegant interface.',
                style: GoogleFonts.inter(
                  fontSize: isMobile ? 14 : 18,
                  color: Colors.white.withOpacity(0.8),
                ),
              ).animate(delay: 400.ms).fadeIn(),
            ],
          ),
        ),
      ),
    );
  }

  // Builds the login form section
  Widget _buildLoginForm(AuthProvider authProvider, {required bool isMobile}) {
    return Container(
      constraints: const BoxConstraints(maxWidth: 400),
      padding: EdgeInsets.symmetric(
        horizontal: isMobile ? 24 : 32,
        vertical: isMobile ? 32 : 0,
      ),
      child: Form(
        key: _formKey,
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text(
              'Welcome back',
              style: GoogleFonts.poppins(
                fontSize: isMobile ? 26 : 32,
                fontWeight: FontWeight.bold,
                color: const Color(0xFF0F172A),
              ),
            ),
            const SizedBox(height: 8),
            Text(
              'Please enter your admin credentials to continue.',
              style: GoogleFonts.inter(
                fontSize: isMobile ? 14 : 16,
                color: const Color(0xFF64748B),
              ),
            ),
            SizedBox(height: isMobile ? 32 : 48),
            TextFormField(
              controller: _emailController,
              decoration: const InputDecoration(
                labelText: 'Email Address / NIP',
                prefixIcon: Icon(
                  Icons.person_outline,
                  color: Color(0xFF64748B),
                ),
              ),
              keyboardType: TextInputType.text,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Please enter your credential';
                }
                return null;
              },
            ),
            const SizedBox(height: 24),
            TextFormField(
              controller: _passwordController,
              decoration: const InputDecoration(
                labelText: 'Password',
                prefixIcon: Icon(
                  Icons.lock_outline,
                  color: Color(0xFF64748B),
                ),
              ),
              obscureText: true,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Please enter your password';
                }
                return null;
              },
            ),
            SizedBox(height: isMobile ? 32 : 40),
            SizedBox(
              height: 56,
              child: ElevatedButton(
                onPressed: authProvider.isLoading ? null : _submit,
                child: authProvider.isLoading
                    ? const SizedBox(
                        width: 24,
                        height: 24,
                        child: CircularProgressIndicator(
                          color: Colors.white,
                          strokeWidth: 2,
                        ),
                      )
                    : const Text(
                        'Sign In to Dashboard',
                        style: TextStyle(fontSize: 16),
                      ),
              ),
            ),
          ],
        )
            .animate()
            .fadeIn(duration: 600.ms)
            .slideY(begin: 0.1, end: 0, curve: Curves.easeOutQuad),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return Scaffold(
      backgroundColor: Colors.white,
      body: LayoutBuilder(
        builder: (context, constraints) {
          final isMobile = constraints.maxWidth < 800;

          if (isMobile) {
            // Mobile: vertical layout - brand header on top, form below
            return SingleChildScrollView(
              child: Column(
                children: [
                  _buildBrandSection(isMobile: true),
                  _buildLoginForm(authProvider, isMobile: true),
                ],
              ),
            );
          }

          // Desktop/Web: side-by-side layout
          return Row(
            children: [
              Expanded(flex: 4, child: _buildBrandSection(isMobile: false)),
              Expanded(
                flex: 5,
                child: Center(
                  child: SingleChildScrollView(
                    child: _buildLoginForm(authProvider, isMobile: false),
                  ),
                ),
              ),
            ],
          );
        },
      ),
    );
  }
}
