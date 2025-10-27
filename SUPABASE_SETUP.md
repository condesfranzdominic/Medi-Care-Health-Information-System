# Supabase Setup Guide for Medi-Care Health Information System

## 📋 Prerequisites
- PHP 8.0+ with PDO PostgreSQL extension
- Supabase account and project
- Local development environment

## 🔧 Step 1: Configure Environment

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Update `.env` with your Supabase credentials:
   ```env
   SUPABASE_DB_HOST=db.<your-supabase-id>.supabase.co
   SUPABASE_DB_NAME=postgres
   SUPABASE_DB_USER=postgres
   SUPABASE_DB_PASS=<your-supabase-password>
   SUPABASE_DB_PORT=6543
   BASE_URL=http://localhost/Medi-Care-Health-Information-System
   ```

   > 💡 Find these values in: Supabase Dashboard → Project Settings → Database

## 🚀 Step 2: Start Development Server

```bash
php -S localhost:8000
```

## 🧪 Step 3: Test Routes

- **Home**: http://localhost:8000/
- **Login**: http://localhost:8000/login
- **Logout**: http://localhost:8000/logout
- **404**: http://localhost:8000/nonexistent

## ✅ Step 4: Verify Database Connection

The system will automatically test the PostgreSQL connection when you access the routes. If there are connection issues, you'll see error messages with details.

## 🎯 What's Configured

- ✅ PostgreSQL (Supabase) database connection
- ✅ Clean URL routing system
- ✅ Basic MVC structure
- ✅ Session management for login/logout
- ✅ Error handling and 404 pages
- ✅ Responsive CSS styling

## 🔍 File Structure

```
Medi-Care-Health-Information-System/
├── .env.example          # Environment template
├── .env                  # Your actual config (gitignored)
├── config/
│   └── Database.php      # PostgreSQL connection class
├── includes/
│   ├── routes.php        # Route definitions
│   └── router.php        # Route handling logic
├── controllers/
│   ├── index.php         # Home page controller
│   ├── login.php         # Login page controller
│   └── logout.php        # Logout handler
├── views/
│   ├── partials/
│   │   ├── header.php    # HTML header
│   │   └── footer.php    # HTML footer
│   └── login.view.php    # Login form
├── public/
│   └── css/
│       └── style.css     # Styling
└── index.php             # Application entry point
```

## 🛠 Next Steps

1. Set up your Supabase database tables using the provided schema
2. Implement authentication logic in the login controller
3. Add more routes and controllers as needed
4. Customize the views and styling

## 🐛 Troubleshooting

- **Database connection fails**: Check your `.env` file credentials
- **Routes not working**: Ensure the PHP server is running from the project root
- **Blank pages**: Check PHP error logs for syntax errors

## 📚 Additional Resources

- [Supabase PHP Documentation](https://supabase.com/docs/reference/php)
- [PDO PostgreSQL Documentation](https://www.php.net/manual/en/ref.pdo-pgsql.php)
