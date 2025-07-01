# Admin Management API Documentation

## Overview
Sistem manajemen admin telah berhasil ditambahkan ke aplikasi Laravel Feedback. Admin dapat mengelola user, mengubah status admin user lain, dan melihat statistik dashboard.

## Endpoints yang Tersedia

### 1. Authentication Endpoints

#### Register First Admin
```
POST /api/register-admin
```
**Deskripsi**: Mendaftarkan admin pertama (hanya bisa digunakan jika belum ada admin)

**Body**:
```json
{
    "name": "Admin Name",
    "email": "admin@example.com",
    "password": "password123",
    "role": "tenaga_pendidik",
    "programStudy": "s1_informatika"
}
```

#### Check Admin Status
```
GET /api/check-admin
```
**Headers**: `Authorization: Bearer {token}`

**Response**:
```json
{
    "is_admin": true,
    "user": {
        "id": 1,
        "name": "Admin Name",
        "email": "admin@example.com",
        "is_admin": true,
        "role": "tenaga_pendidik"
    }
}
```

### 2. Admin Management Endpoints

#### Get All Users (dengan pagination dan filter)
```
GET /api/admin/users?page=1&search=john&role=mahasiswa
```
**Headers**: `Authorization: Bearer {token}` (Admin only)

**Query Parameters**:
- `page`: Nomor halaman (optional)
- `search`: Pencarian berdasarkan nama atau email (optional)
- `role`: Filter berdasarkan role (mahasiswa/tenaga_pendidik) (optional)

**Response**:
```json
{
    "users": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "mahasiswa",
            "programStudy": "s1_informatika",
            "is_admin": false,
            "created_at": "2025-06-24T10:00:00.000000Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    }
}
```

#### Toggle Admin Status
```
PUT /api/admin/users/{id}/toggle-admin
```
**Headers**: `Authorization: Bearer {token}` (Admin only)

**Response**:
```json
{
    "message": "User admin status updated successfully",
    "user": {
        "id": 2,
        "name": "John Doe",
        "email": "john@example.com",
        "is_admin": true
    },
    "is_admin": true
}
```

#### Update User Information
```
PUT /api/admin/users/{id}
```
**Headers**: `Authorization: Bearer {token}` (Admin only)

**Body** (semua field optional):
```json
{
    "name": "Updated Name",
    "email": "newemail@example.com",
    "role": "tenaga_pendidik",
    "programStudy": "s1_manajemen",
    "is_admin": true
}
```

#### Delete User
```
DELETE /api/admin/users/{id}
```
**Headers**: `Authorization: Bearer {token}` (Admin only)

**Response**:
```json
{
    "message": "User deleted successfully"
}
```

#### Get Dashboard Statistics
```
GET /api/admin/stats
```
**Headers**: `Authorization: Bearer {token}` (Admin only)

**Response**:
```json
{
    "stats": {
        "total_users": 150,
        "total_admins": 3,
        "total_mahasiswa": 120,
        "total_tenaga_pendidik": 30,
        "recent_users": 15
    }
}
```

## Flow Penggunaan

### 1. Setup Admin Pertama
1. Gunakan endpoint `POST /api/register-admin` untuk membuat admin pertama
2. Login menggunakan kredensial admin tersebut

### 2. Manajemen User oleh Admin
1. Admin login dan mendapatkan JWT token
2. Cek status admin dengan `GET /api/check-admin`
3. Lihat daftar user dengan `GET /api/admin/users`
4. Jadikan user sebagai admin dengan `PUT /api/admin/users/{id}/toggle-admin`
5. Edit informasi user dengan `PUT /api/admin/users/{id}`
6. Hapus user dengan `DELETE /api/admin/users/{id}`

### 3. Frontend Implementation Example (JavaScript)

```javascript
// Check if user is admin
async function checkAdminStatus() {
    const response = await fetch('/api/check-admin', {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'Content-Type': 'application/json'
        }
    });
    const data = await response.json();
    return data.is_admin;
}

// Get all users for admin dashboard
async function getAllUsers(page = 1, search = '', role = '') {
    const params = new URLSearchParams({
        page: page,
        ...(search && { search }),
        ...(role && { role })
    });
    
    const response = await fetch(`/api/admin/users?${params}`, {
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'Content-Type': 'application/json'
        }
    });
    return await response.json();
}

// Toggle admin status
async function toggleAdminStatus(userId) {
    const response = await fetch(`/api/admin/users/${userId}/toggle-admin`, {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
            'Content-Type': 'application/json'
        }
    });
    return await response.json();
}
```

## Keamanan

1. **Middleware Protection**: Semua endpoint admin dilindungi middleware `admin` yang memastikan hanya admin yang bisa akses
2. **Self-Protection**: Admin tidak bisa menghapus akun sendiri atau menghilangkan status admin sendiri
3. **First Admin Only**: Endpoint `register-admin` hanya bisa digunakan jika belum ada admin di sistem
4. **JWT Authentication**: Semua endpoint memerlukan JWT token yang valid

## Error Responses

- `401 Unauthorized`: Token tidak valid atau tidak ada
- `403 Forbidden`: User bukan admin
- `404 Not Found`: User tidak ditemukan
- `422 Unprocessable Entity`: Validasi error atau operasi tidak diizinkan
