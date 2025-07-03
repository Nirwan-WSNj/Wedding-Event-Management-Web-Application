# Wedding Event Management Web Application

A comprehensive Laravel-based wedding management system designed for wedding venues and event planners.

## Features

### 🎯 Multi-Role System
- **Admin Dashboard**: Complete system management
- **Manager Dashboard**: Venue operations and booking workflow
- **Customer Portal**: Booking and event planning

### 💼 Core Functionality
- **Venue Management**: Multiple wedding halls with availability tracking
- **Package Management**: Customizable wedding packages
- **Booking System**: 6-step booking workflow with manager approval
- **Payment Processing**: Advance payment and confirmation system
- **Visit Scheduling**: Customer venue visits with manager approval

### 🛠️ Technical Features
- **Laravel 11**: Modern PHP framework
- **Real-time Dashboard**: Live statistics and updates
- **Role-based Access Control**: Secure multi-user system
- **Responsive Design**: Mobile-friendly interface
- **Database Integration**: MySQL with comprehensive data structure

## Installation

1. Clone the repository
```bash
git clone https://github.com/Nirwan-WSNj/Wedding-Event-Management-Web-Application.git
cd Wedding-Event-Management-Web-Application
```

2. Install dependencies
```bash
composer install
npm install
```

3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

4. Database setup
```bash
# Import the provided database structure
mysql -u your_username -p your_database < wmdemo_db.sql

# Or run migrations
php artisan migrate --seed
```

5. Build assets
```bash
npm run build
```

6. Start the application
```bash
php artisan serve
```

## Default Users

### Admin Access
- **Email**: admin@wmdemo.com
- **Password**: admin123

### Manager Access
- **Email**: manager@wmdemo.com
- **Password**: manager123

## System Architecture

### Admin Features
- User management (CRUD operations)
- Venue and package management
- Booking oversight and analytics
- System configuration and monitoring

### Manager Features
- Visit request approval/rejection
- Payment confirmation workflow
- Booking status management
- Customer communication

### Customer Features
- Venue browsing and package selection
- Online booking with customization
- Visit scheduling
- Booking status tracking

## Technology Stack

- **Backend**: Laravel 11, PHP 8.1+
- **Frontend**: Blade Templates, Tailwind CSS, JavaScript
- **Database**: MySQL 8.0+
- **Icons**: Remix Icons
- **Charts**: Chart.js for analytics

## Production Ready

This system is production-ready with:
- ✅ Complete functionality testing
- ✅ Security implementation
- ✅ Performance optimization
- ✅ Error handling
- ✅ Clean code structure

## License

This project is proprietary software developed for wedding venue management.

## Support

For support and inquiries, please contact the development team.

---

**Status**: Production Ready ✅  
**Version**: 1.0.0  
**Last Updated**: 2024