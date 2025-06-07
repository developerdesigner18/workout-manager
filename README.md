# Workout Manager

A comprehensive workout session management platform for gyms built with Laravel, Filament Admin Panel, and Livewire. This application provides a complete solution for managing workout sessions with role-based access control, API endpoints, and modern web interface.

## 🚀 Features

### Core Features
- User Authentication: Register, login, logout with Laravel Sanctum
- Workout Management: Full CRUD operations for workout sessions
- Role-Based Access Control: Admin and regular user roles
- Soft Deletes: Safe deletion with restore capability
- Search & Filter: Real-time search by title and trainer
- Responsive Design: Mobile-friendly Tailwind CSS interface

### Admin Panel (Filament)
- User Management: Complete CRUD for users with role assignment
- Workout Management: Admin oversight of all workouts
- Advanced Filtering: Filter by user, status, and soft-deleted items
- Bulk Operations: Mass actions for efficient management
- Restore Functionality: Recover soft-deleted workouts

### API Features
- RESTful API: Complete workout management API
- Token Authentication: Secure API access with Sanctum
- Interactive Documentation: Swagger/OpenAPI documentation
- Request Validation: Comprehensive form request validation
- API Resources: Consistent JSON responses

### Frontend (Livewire)
- Real-time Interface: Dynamic updates without page refresh
- Modal Forms: Smooth create/edit experience
- Validation Feedback: Real-time form validation
- Statistics Dashboard: Workout stats and overview
- Search & Filter: Live search functionality

## 🛠 Tech Stack

- Backend: Laravel 12.x
- Admin Panel: Filament 3.x
- Frontend: Livewire 3.x with Tailwind CSS
- Authentication: Laravel Sanctum
- API Documentation: L5-Swagger (OpenAPI 3.0)
- Database: MySQL 8.0
- Styling: Tailwind CSS

## 📋 Requirements

### 
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM

## 🚀 Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/workout-manager.git
cd workout-manager
```

2. Install dependencies
```bash
composer install
npm install
```

3. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Update .env file with your database credentials
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workout_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

5. Run migrations and seeders
```bash
php artisan migrate
php artisan db:seed
```

6. Generate API documentation
```bash
php artisan l5-swagger:generate 
```

7. Compile assets
```bash
npm run build 
```

8. Start the development server
```
php artisan serve
```

## 👥 Default Users

### Admin User

- Email: [admin@example.com](mailto:admin@example.com)
- Password: password
- Role: admin
- Access: Full admin panel access + all user features


### Regular User

- Email: [user@example.com](mailto:user@example.com)
- Password: password
- Role: user
- Access: Frontend dashboard and API access only

## 📚 API Documentation

### Access Points

- Interactive Documentation: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
- JSON Schema: [http://localhost:8000/docs/api-docs.json](http://localhost:8000/docs/api-docs.json)


### Authentication Endpoints

#### Register User

```plaintext
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login

```plaintext
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Logout

```plaintext
POST /api/logout
Authorization: Bearer YOUR_TOKEN_HERE
```

### Workout Endpoints

#### Get All Workouts

```plaintext
GET /api/workouts
Authorization: Bearer YOUR_TOKEN_HERE
```

#### Create Workout

```plaintext
POST /api/workouts
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
  "title": "Power Training",
  "description": "Intensive leg day workout",
  "trainer": "John Doe",
  "date": "2025-06-07T18:00:00",
  "slots": 10,
  "is_active": true
}
```

#### Get Specific Workout

```plaintext
GET /api/workouts/{id}
Authorization: Bearer YOUR_TOKEN_HERE
```

#### Update Workout

```plaintext
PUT /api/workouts/{id}
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
  "title": "Updated Power Training",
  "slots": 15
}
```

#### Delete Workout

```plaintext
DELETE /api/workouts/{id}
Authorization: Bearer YOUR_TOKEN_HERE
```

## 🗂 Database Schema

### Users Table

| Field | Type | Description
|-----|-----|-----
| id | int | Primary key
| name | string | User's full name
| email | string | Unique email address
| role | enum | 'admin' or 'user'
| email_verified_at | timestamp | Email verification time
| password | string | Hashed password
| created_at | timestamp | Creation time
| updated_at | timestamp | Last update time


### Workouts Table

| Field | Type | Description
|-----|-----|-----
| id | int | Primary key
| title | string | Workout title
| description | text | Workout description
| trainer | string | Trainer's name
| date | datetime | Workout date and time
| slots | int | Available slots
| is_active | boolean | Public visibility
| user_id | int | Creator (foreign key)
| deleted_at | timestamp | Soft delete timestamp
| created_at | timestamp | Creation time
| updated_at | timestamp | Last update time

## 🧪 Testing

### API Testing with cURL

```shellscript
# Register a new user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login and get token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Create a workout (replace YOUR_TOKEN with actual token)
curl -X POST http://localhost:8000/api/workouts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"title":"Morning Yoga","description":"Relaxing yoga session","trainer":"Jane Doe","date":"2025-06-07T08:00:00","slots":20,"is_active":true}'
```

## 🆘 Support

If you encounter any issues or have questions:

1. Check the API documentation ([http://localhost:8000/api/documentation](http://localhost:8000/api/documentation))
2. Ensure all environment variables are properly set
3. Verify database connectivity


For additional support, please open an issue in the repository.

## 🏗 Project Structure

```plaintext
workout-manager/
├── app/
│   ├── Filament/Resources/          # Filament admin resources
│   │   ├── UserResource.php
│   │   └── WorkoutResource.php
│   ├── Http/
│   │   ├── Controllers/Api/         # API controllers
│   │   │   ├── AuthController.php
│   │   │   └── WorkoutController.php
│   │   ├── Requests/               # Form request validation
│   │   │   ├── StoreWorkoutRequest.php
│   │   │   └── UpdateWorkoutRequest.php
│   │   ├── Resources/              # API resources
│   │   │   └── WorkoutResource.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Livewire/                   # Livewire components
│   │   ├── Auth/
│   │   │   ├── Login.php
│   │   │   └── Register.php
│   │   └── Dashboard.php
│   ├── Models/                     # Eloquent models
│   │   ├── User.php
│   │   └── Workout.php
│   └── Policies/
│       └── WorkoutPolicy.php
├── database/
│   ├── migrations/                 # Database migrations
│   └── seeders/                   # Database seeders
│       └── AdminUserSeeder.php
├── resources/views/livewire/       # Livewire views
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   └── dashboard.blade.php
├── routes/
│   ├── api.php                    # API routes
│   └── web.php                    # Web routes
└── README.md                      # This file
```
