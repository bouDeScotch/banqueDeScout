# Banque De Scout

**Banque De Scout** is a fictional banking web application designed for fun and educational purposes. This project simulates a playful banking experience, allowing users to "earn" weekly interest, request personalized checkbooks, and manage their virtual currency called **ARO**. 

## Website

It is currently hosted on [floblok.com](https://floblok.com), but will probably move. If you do not have an account, you cannot create one because I decided to do it only using admin panel. I might change it in the future, with a guest account probably.

## Features
- **Account Management**: Create and manage your account with a unique username and password.
- **Admin Panel**: Admins can create account, credit and debit accounts, and reset user passwords.
- **Everyday Interest**: Users automatically earn interest on their balance every week.

## Deploy it Yourself

### Project structure
- `/public/` — Main public-facing pages.
- `/assets/` — CSS, images, and other static assets.
- `/admin/` — Admin interface.
- `/api/` — API endpoints (if any). They mostly check for token validity then return response from files in `/routes/`.
- `/data/` — JSON files for user data and other configurations. It if hidden on github because it contains :
  - `admin_tokens.json` — Admin tokens for authentication.
  - `clients.json` — User account data.
  - `tokens.json` — List of valid tokens for API access.
- `/routes/` — Server-side routes for handling requests.
- `.htaccess` — URL rewriting and security rules.

## Disclaimer

> **This is not a real bank. Banque De Scout is a fictional project and should not be used for real financial transactions. All features and currencies are for entertainment and learning only. It was only used with friend but I decided to make it public**
