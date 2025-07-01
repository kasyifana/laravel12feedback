# Feedback API Endpoints

## Public Endpoints (Tidak perlu authentication)

### 1. Get All Feedback
```
GET /api/feedback
```
**Query Parameters:**
- `page` (optional): Halaman pagination
- `rating` (optional): Filter berdasarkan rating (1-5)
- `has_reply` (optional): Filter berdasarkan status balasan (true/false)

**Response:**
```json
{
    "feedback": [
        {
            "id": 1,
            "user_id": 1,
            "rating": 5,
            "komentar": "Aplikasi sangat bagus!",
            "balasan": "Terima kasih atas feedbacknya",
            "created_at": "2025-06-24T16:00:00.000000Z",
            "updated_at": "2025-06-24T16:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 10,
        "total": 1
    }
}
```

### 2. Create Feedback (Anonymous atau Authenticated)
```
POST /api/feedback
```
**Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer {token}` (optional - jika user login)

**Body:**
```json
{
    "rating": 5,
    "komentar": "Aplikasi sangat membantu dalam pembelajaran"
}
```

### 3. Get Feedback by ID
```
GET /api/feedback/{id}
```

### 4. Get Feedback Statistics
```
GET /api/feedback/stats
```

## Authenticated User Endpoints

### 5. Get My Feedback
```
GET /api/feedback/my
```
**Headers:**
- `Authorization: Bearer {token}`

### 6. Update My Feedback
```
PUT /api/feedback/{id}
```
**Headers:**
- `Authorization: Bearer {token}`
- `Content-Type: application/json`

**Body:**
```json
{
    "rating": 4,
    "komentar": "Update komentar saya"
}
```

### 7. Delete My Feedback
```
DELETE /api/feedback/{id}
```
**Headers:**
- `Authorization: Bearer {token}`

## Admin Only Endpoints

### 8. Get All Feedback (Admin View)
```
GET /api/admin/feedback
```
**Headers:**
- `Authorization: Bearer {admin-token}`

### 9. Reply to Feedback
```
PUT /api/admin/feedback/{id}/reply
```
**Headers:**
- `Authorization: Bearer {admin-token}`
- `Content-Type: application/json`

**Body:**
```json
{
    "balasan": "Terima kasih atas feedback Anda. Kami akan terus meningkatkan layanan."
}
```

### 10. Delete Feedback (Admin)
```
DELETE /api/admin/feedback/{id}
```
**Headers:**
- `Authorization: Bearer {admin-token}`

## Example Usage

### 1. Create Anonymous Feedback
```bash
curl -X POST "http://localhost:8000/api/feedback" \
     -H "Content-Type: application/json" \
     -d '{
         "rating": 5,
         "komentar": "Aplikasi sangat bagus dan mudah digunakan!"
     }'
```

### 2. Create Authenticated Feedback
```bash
curl -X POST "http://localhost:8000/api/feedback" \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer YOUR_JWT_TOKEN" \
     -d '{
         "rating": 4,
         "komentar": "Fitur feedback ini sangat membantu"
     }'
```

### 3. Admin Reply to Feedback
```bash
curl -X PUT "http://localhost:8000/api/admin/feedback/1/reply" \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer ADMIN_JWT_TOKEN" \
     -d '{
         "balasan": "Terima kasih atas masukan Anda. Kami akan terus berinovasi."
     }'
```

### 4. Get Feedback with Filters
```bash
# Get feedback dengan rating 5
curl "http://localhost:8000/api/feedback?rating=5"

# Get feedback yang belum ada balasan
curl "http://localhost:8000/api/feedback?has_reply=false"

# Get feedback halaman 2
curl "http://localhost:8000/api/feedback?page=2"
```

## Error Responses

### 404 - Not Found
```json
{
    "error": "Feedback tidak ditemukan"
}
```

### 403 - Forbidden
```json
{
    "error": "Anda hanya bisa mengedit feedback sendiri"
}
```

### 422 - Validation Error
```json
{
    "message": "The rating field is required.",
    "errors": {
        "rating": ["The rating field is required."],
        "komentar": ["The komentar field is required."]
    }
}
```

## Database Schema

```sql
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL, -- Nullable untuk feedback anonim
    rating INT NOT NULL, -- Skala 1-5
    komentar TEXT NOT NULL, -- Isi feedback
    balasan TEXT NULL, -- Balasan dari admin
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
);
```
