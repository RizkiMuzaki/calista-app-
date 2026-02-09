# Panduan Penggunaan Token Authentication

## 1. Login dan Dapatkan Token

```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Login berhasil",
  "authenticated": true,
  "data": {
    "user": {...},
    "token": "your_token_here",
    "token_type": "Bearer",
    "usage": "Kirim header: Authorization: Bearer your_token_here"
  }
}
```

## 2. Gunakan Token di Setiap Request

**PENTING**: Setelah login, **simpan token** dan kirim di setiap request yang memerlukan autentikasi:

```bash
POST /api/modules
Authorization: Bearer your_token_here
Content-Type: application/json

{
  "name": "Module Baru",
  "description": "Deskripsi module"
}
```

## 3. Check Authentication Status

Untuk memverifikasi user sudah login:

```bash
GET /api/auth/check
Authorization: Bearer your_token_here
```

**Response (Terautentikasi):**
```json
{
  "status": "success",
  "authenticated": true,
  "user": {...}
}
```

**Response (Belum Login):**
```json
{
  "status": "error",
  "authenticated": false,
  "message": "Belum login"
}
```

## 4. Contoh di Flutter/Mobile

```dart
// Login
final response = await http.post(
  Uri.parse('http://localhost:8000/api/auth/login'),
  headers: {'Content-Type': 'application/json'},
  body: jsonEncode({
    'email': 'user@example.com',
    'password': 'password123',
  }),
);

if (response.statusCode == 200) {
  final data = jsonDecode(response.body);
  final token = data['data']['token'];
  
  // Simpan token (gunakan local storage)
  await storage.write(key: 'auth_token', value: token);
  
  print('Token: ${data['data']['usage']}');
}

// Gunakan token di request berikutnya
final headers = {
  'Content-Type': 'application/json',
  'Authorization': 'Bearer $token',
};

final moduleResponse = await http.post(
  Uri.parse('http://localhost:8000/api/modules'),
  headers: headers,
  body: jsonEncode({
    'name': 'Module Baru',
  }),
);
```

## 5. Troubleshooting

### 401 Unauthorized pada POST /api/modules
- ✅ Pastikan sudah login dan mendapat token
- ✅ Pastikan token dikirim di header `Authorization: Bearer {token}`
- ✅ Pastikan format header tepat (ada spasi antara "Bearer" dan token)
- ✅ Pastikan token belum expired

### Token tidak dikirim
- Gunakan Postman/Insomnia untuk test
- Di tab "Headers", tambahkan: `Authorization: Bearer your_token_here`

### Logout
```bash
POST /api/auth/logout
Authorization: Bearer your_token_here
```

## Summary

| Endpoint | Auth | Purpose |
|----------|------|---------|
| POST /api/auth/login | ❌ | Login & dapatkan token |
| POST /api/auth/register | ❌ | Register user baru |
| GET /api/auth/check | ✅ | Check auth status |
| GET /api/auth/me | ✅ | Get user data |
| POST /api/auth/logout | ✅ | Logout & hapus token |
| POST /api/modules | ✅ | Buat module (admin) |
| GET /api/modules | ❌ | List semua module |
