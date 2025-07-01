# Laporan API Documentation

This document outlines the API endpoints available for the Laporan (Reports) feature.

## Base URL

```
/api
```

## Authentication

Some endpoints require authentication using a JWT token. Include the token in the Authorization header:

```
Authorization: Bearer {token}
```

## Endpoints

### 1. Get All Laporan

Retrieves a list of all laporan/reports.

- **URL**: `/laporan`
- **Method**: `GET`
- **Auth Required**: No
- **Response**:
  ```json
  {
    "success": true,
    "message": "List of all laporan",
    "data": [
      {
        "id_laporan": 1,
        "judul": "Example Report Title",
        "kategori": "Teknis",
        "deskripsi": "Description of the issue...",
        "prioritas": "Medium",
        "status": "Pending",
        "tanggal_lapor": "2025-06-27",
        "waktu_lapor": "14:30:00",
        "nama_pelapor": "John Doe",
        "lampiran": "lampiran/example.pdf",
        "respon": null,
        "oleh": null,
        "waktu_respon": null,
        "created_at": "2025-06-27T14:30:00.000000Z",
        "updated_at": "2025-06-27T14:30:00.000000Z"
      }
    ]
  }
  ```

### 2. Get Laporan by ID

Retrieves a specific laporan by its ID.

- **URL**: `/laporan/{id}`
- **Method**: `GET`
- **Auth Required**: No
- **Response**:
  ```json
  {
    "success": true,
    "data": {
      "id_laporan": 1,
      "judul": "Example Report Title",
      "kategori": "Teknis",
      "deskripsi": "Description of the issue...",
      "prioritas": "Medium",
      "status": "Pending",
      "tanggal_lapor": "2025-06-27",
      "waktu_lapor": "14:30:00",
      "nama_pelapor": "John Doe",
      "lampiran": "lampiran/example.pdf",
      "respon": null,
      "oleh": null,
      "waktu_respon": null,
      "created_at": "2025-06-27T14:30:00.000000Z",
      "updated_at": "2025-06-27T14:30:00.000000Z"
    }
  }
  ```

### 3. Get Laporan by Status

Retrieves all laporan with a specific status.

- **URL**: `/laporan/status/{status}`
- **Method**: `GET`
- **Auth Required**: No
- **Parameters**:
  - `status`: One of `Pending`, `In Progress`, or `Selesai`
- **Response**:
  ```json
  {
    "success": true,
    "message": "List of Pending laporan",
    "data": [
      {
        "id_laporan": 1,
        "judul": "Example Report Title",
        "kategori": "Teknis",
        "deskripsi": "Description of the issue...",
        "prioritas": "Medium",
        "status": "Pending",
        "tanggal_lapor": "2025-06-27",
        "waktu_lapor": "14:30:00",
        "nama_pelapor": "John Doe",
        "lampiran": "lampiran/example.pdf",
        "respon": null,
        "oleh": null,
        "waktu_respon": null,
        "created_at": "2025-06-27T14:30:00.000000Z",
        "updated_at": "2025-06-27T14:30:00.000000Z"
      }
    ]
  }
  ```

### 4. Create a New Laporan

Creates a new laporan/report.

- **URL**: `/laporan`
- **Method**: `POST`
- **Auth Required**: No
- **Content-Type**: `multipart/form-data`
- **Request Parameters**:
  - `judul` (required): Title of the report
  - `kategori` (required): Category of the report
  - `deskripsi` (required): Detailed description
  - `prioritas` (required): `Low`, `Medium`, or `High`
  - `tanggal_lapor` (required): Date in YYYY-MM-DD format
  - `waktu_lapor` (required): Time in HH:MM:SS format
  - `nama_pelapor` (optional): Name of the reporter
  - `lampiran` (optional): File attachment (image or document)
- **Response**:
  ```json
  {
    "success": true,
    "message": "Laporan created successfully",
    "data": {
      "judul": "Example Report Title",
      "kategori": "Teknis",
      "deskripsi": "Description of the issue...",
      "prioritas": "Medium",
      "status": "Pending",
      "tanggal_lapor": "2025-06-27",
      "waktu_lapor": "14:30:00",
      "nama_pelapor": "John Doe",
      "lampiran": "lampiran/example.pdf",
      "id_laporan": 1,
      "created_at": "2025-06-27T14:30:00.000000Z",
      "updated_at": "2025-06-27T14:30:00.000000Z"
    }
  }
  ```

### 5. Update a Laporan

Updates an existing laporan.

- **URL**: `/laporan/{id}`
- **Method**: `PUT`
- **Auth Required**: Yes
- **Content-Type**: `multipart/form-data`
- **Request Parameters**:
  - `judul` (optional): Title of the report
  - `kategori` (optional): Category of the report
  - `deskripsi` (optional): Detailed description
  - `prioritas` (optional): `Low`, `Medium`, or `High`
  - `status` (optional): `Pending`, `In Progress`, or `Selesai`
  - `tanggal_lapor` (optional): Date in YYYY-MM-DD format
  - `waktu_lapor` (optional): Time in HH:MM:SS format
  - `nama_pelapor` (optional): Name of the reporter
  - `lampiran` (optional): File attachment (image or document)
  - `respon` (optional): Response to the report
  - `oleh` (optional): Person who responded
- **Response**:
  ```json
  {
    "success": true,
    "message": "Laporan updated successfully",
    "data": {
      "id_laporan": 1,
      "judul": "Updated Report Title",
      "kategori": "Teknis",
      "deskripsi": "Updated description...",
      "prioritas": "High",
      "status": "In Progress",
      "tanggal_lapor": "2025-06-27",
      "waktu_lapor": "14:30:00",
      "nama_pelapor": "John Doe",
      "lampiran": "lampiran/new_example.pdf",
      "respon": "We are working on your issue",
      "oleh": "Support Team",
      "waktu_respon": "2025-06-28T10:15:00.000000Z",
      "created_at": "2025-06-27T14:30:00.000000Z",
      "updated_at": "2025-06-28T10:15:00.000000Z"
    }
  }
  ```

### 6. Delete a Laporan

Deletes a laporan.

- **URL**: `/laporan/{id}`
- **Method**: `DELETE`
- **Auth Required**: Yes
- **Response**:
  ```json
  {
    "success": true,
    "message": "Laporan deleted successfully"
  }
  ```

## Error Responses

### Not Found (404)
```json
{
  "success": false,
  "message": "Laporan not found"
}
```

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {
    "judul": ["The judul field is required."],
    "prioritas": ["The prioritas field must be one of: Low, Medium, High."]
  }
}
```

### Invalid Status (400)
```json
{
  "success": false,
  "message": "Invalid status parameter"
}
```
