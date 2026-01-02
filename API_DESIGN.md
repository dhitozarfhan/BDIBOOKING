# REST API Design untuk Aplikasi Mobile BDIYK

## Daftar Isi
1. [Autentikasi](#autentikasi)
2. [Endpoint Articles](#endpoint-articles)
3. [Endpoint Training](#endpoint-training)
4. [Endpoint Competency](#endpoint-competency)
5. [Endpoint Information Request](#endpoint-information-request)
6. [Endpoint Gratification](#endpoint-gratification)
7. [Endpoint WBS](#endpoint-wbs)
8. [Endpoint Slideshow](#endpoint-slideshow)
9. [Response Format](#response-format)

## Base URL
```
https://your-domain.com/api/v1
```

## Autentikasi

### 1. Login
**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
  "username": "string",
  "password": "string"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "Bearer token",
    "user": {
      "id": 1,
      "username": "john_doe",
      "name": "John Doe",
      "email": "john@example.com",
      "image": "url_to_image",
      "position": "Position Name"
    }
  }
}
```

### 2. Logout
**Endpoint:** `POST /auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "message": "Logout successful"
}
```

### 3. Get User Profile
**Endpoint:** `GET /auth/profile`

**Headers:** `Authorization: Bearer {token}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "username": "john_doe",
    "name": "John Doe",
    "email": "john@example.com",
    "nip": "123456789",
    "position": "Position Name",
    "image": "url_to_image"
  }
}
```

## Endpoint Articles

### 1. Get All Articles (News/Gallery/Page)
**Endpoint:** `GET /articles`

**Query Parameters:**
- `type` (optional): news, gallery, page, information
- `category_id` (optional): filter by category
- `page` (optional): page number (default: 1)
- `per_page` (optional): items per page (default: 10)
- `search` (optional): search by title

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Article Title",
        "slug": "article-title-1",
        "summary": "Article summary",
        "image": "url_to_image",
        "category": {
          "id": 1,
          "name": "Category Name"
        },
        "author": {
          "id": 1,
          "name": "Author Name"
        },
        "published_at": "2025-01-01T00:00:00Z",
        "hit": 100
      }
    ],
    "total": 50,
    "per_page": 10,
    "last_page": 5
  }
}
```

### 2. Get Article Detail
**Endpoint:** `GET /articles/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Article Title",
    "slug": "article-title-1",
    "summary": "Article summary",
    "content": "Full article content",
    "image": "url_to_image",
    "category": {
      "id": 1,
      "name": "Category Name"
    },
    "author": {
      "id": 1,
      "name": "Author Name",
      "image": "url_to_image"
    },
    "tags": [
      {
        "id": 1,
        "name": "Tag Name"
      }
    ],
    "images": [
      {
        "id": 1,
        "url": "url_to_image",
        "caption": "Image caption"
      }
    ],
    "published_at": "2025-01-01T00:00:00Z",
    "hit": 100
  }
}
```

### 3. Get Categories
**Endpoint:** `GET /categories`

**Query Parameters:**
- `type` (optional): news, gallery, page, information

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Category Name",
      "slug": "category-name",
      "articles_count": 10
    }
  ]
}
```

## Endpoint Training

### 1. Get All Trainings
**Endpoint:** `GET /trainings`

**Query Parameters:**
- `page` (optional): page number
- `per_page` (optional): items per page
- `search` (optional): search by title

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id_diklat": "123",
        "title": "Training Title",
        "slug": "training-title",
        "description": "Training description",
        "image": "url_to_image",
        "start_date": "2025-01-01",
        "end_date": "2025-01-05",
        "location": "Training Location",
        "quota": 30,
        "registered": 15
      }
    ],
    "total": 20,
    "per_page": 10,
    "last_page": 2
  }
}
```

### 2. Get Training Detail
**Endpoint:** `GET /trainings/{id_diklat}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id_diklat": "123",
    "title": "Training Title",
    "slug": "training-title",
    "description": "Full training description",
    "image": "url_to_image",
    "start_date": "2025-01-01",
    "end_date": "2025-01-05",
    "location": "Training Location",
    "quota": 30,
    "registered": 15,
    "requirements": "Training requirements",
    "schedule": "Training schedule"
  }
}
```

### 3. Register for Training
**Endpoint:** `POST /trainings/{id_diklat}/register`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "participant_type": "internal/external",
  "notes": "Additional notes"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "registration_id": 1,
    "status": "pending"
  }
}
```

## Endpoint Competency

### 1. Get SKKNI List
**Endpoint:** `GET /competency/skkni`

**Query Parameters:**
- `page` (optional): page number
- `per_page` (optional): items per page
- `search` (optional): search by title

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": "uuid",
        "code": "SKKNI-001",
        "title": "SKKNI Title",
        "slug": "skkni-title",
        "description": "SKKNI description",
        "year": 2025
      }
    ],
    "total": 30,
    "per_page": 10,
    "last_page": 3
  }
}
```

### 2. Get SKKNI Detail
**Endpoint:** `GET /competency/skkni/{id}`

**Response:**
```json
{
  "success": true,
  "data": {
    "id": "uuid",
    "code": "SKKNI-001",
    "title": "SKKNI Title",
    "slug": "skkni-title",
    "description": "Full SKKNI description",
    "year": 2025,
    "document_url": "url_to_document",
    "units": [
      {
        "id": 1,
        "code": "UNIT-001",
        "title": "Unit Title"
      }
    ]
  }
}
```

### 3. Get LSP List
**Endpoint:** `GET /competency/lsp`

**Query Parameters:**
- `page` (optional): page number
- `per_page` (optional): items per page
- `search` (optional): search by name

**Response:**
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "LSP Name",
        "slug": "lsp-name",
        "address": "LSP Address",
        "phone": "021-1234567",
        "email": "lsp@example.com"
      }
    ],
    "total": 20,
    "per_page": 10,
    "last_page": 2
  }
}
```

## Endpoint Information Request

### 1. Submit Question
**Endpoint:** `POST /information/questions`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "081234567890",
  "identity_number": "1234567890123456",
  "address": "Full address",
  "question": "Question content",
  "question_type": "question_type"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Question submitted successfully",
  "data": {
    "registration_code": "REG-2025-001",
    "status": "pending"
  }
}
```

### 2. Check Question Status
**Endpoint:** `GET /information/questions/{registration_code}`

**Response:**
```json
{
  "success": true,
  "data": {
    "registration_code": "REG-2025-001",
    "name": "John Doe",
    "question": "Question content",
    "status": "answered",
    "answer": "Answer content",
    "answered_at": "2025-01-02T00:00:00Z"
  }
}
```

### 3. Submit Information Request
**Endpoint:** `POST /information/requests`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "081234567890",
  "identity_number": "1234567890123456",
  "address": "Full address",
  "request_type": "request_type",
  "information_needed": "Information needed",
  "purpose": "Purpose of request"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Information request submitted successfully",
  "data": {
    "registration_code": "REQ-2025-001",
    "status": "pending"
  }
}
```

### 4. Check Information Request Status
**Endpoint:** `GET /information/requests/{registration_code}`

**Response:**
```json
{
  "success": true,
  "data": {
    "registration_code": "REQ-2025-001",
    "name": "John Doe",
    "information_needed": "Information needed",
    "status": "processed",
    "response": "Response content",
    "documents": [
      {
        "name": "Document Name",
        "url": "url_to_document"
      }
    ],
    "responded_at": "2025-01-02T00:00:00Z"
  }
}
```

## Endpoint Gratification

### 1. Submit Gratification Report
**Endpoint:** `POST /gratification/reports`

**Headers:** `Authorization: Bearer {token}` (optional)

**Request Body:**
```json
{
  "reporter_name": "John Doe",
  "reporter_email": "john@example.com",
  "reporter_phone": "081234567890",
  "incident_date": "2025-01-01",
  "incident_location": "Location",
  "gratification_type": "money/goods/service",
  "gratification_value": 1000000,
  "description": "Detailed description",
  "evidence_files": ["file1.pdf", "file2.jpg"]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Gratification report submitted successfully",
  "data": {
    "report_code": "GRAT-2025-001",
    "status": "pending"
  }
}
```

### 2. Check Gratification Report Status
**Endpoint:** `GET /gratification/reports/{report_code}`

**Response:**
```json
{
  "success": true,
  "data": {
    "report_code": "GRAT-2025-001",
    "reporter_name": "John Doe",
    "incident_date": "2025-01-01",
    "status": "processed",
    "response": "Response from admin",
    "responded_at": "2025-01-02T00:00:00Z"
  }
}
```

## Endpoint WBS (Whistleblowing System)

### 1. Submit WBS Report
**Endpoint:** `POST /wbs/reports`

**Request Body:**
```json
{
  "reporter_name": "Anonymous",
  "reporter_email": "anonymous@example.com",
  "reporter_phone": "081234567890",
  "violation_type": "corruption/fraud/abuse",
  "violation_date": "2025-01-01",
  "violation_location": "Location",
  "perpetrator_name": "Perpetrator Name",
  "perpetrator_position": "Position",
  "description": "Detailed description",
  "evidence_files": ["file1.pdf", "file2.jpg"]
}
```

**Response:**
```json
{
  "success": true,
  "message": "WBS report submitted successfully",
  "data": {
    "report_code": "WBS-2025-001",
    "status": "pending"
  }
}
```

### 2. Check WBS Report Status
**Endpoint:** `GET /wbs/reports/{report_code}`

**Response:**
```json
{
  "success": true,
  "data": {
    "report_code": "WBS-2025-001",
    "violation_type": "corruption",
    "violation_date": "2025-01-01",
    "status": "investigating",
    "response": "Response from admin",
    "responded_at": "2025-01-02T00:00:00Z"
  }
}
```

## Endpoint Slideshow

### 1. Get Active Slideshows
**Endpoint:** `GET /slideshows`

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Slideshow Title",
      "image": "url_to_image",
      "link": "url_to_link",
      "order": 1
    }
  ]
}
```

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Optional success message",
  "data": {}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error detail"]
  }
}
```

### HTTP Status Codes
- `200 OK`: Request successful
- `201 Created`: Resource created successfully
- `400 Bad Request`: Invalid request
- `401 Unauthorized`: Authentication required
- `403 Forbidden`: Access denied
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error
