# Developer Portfolio

A production-grade personal developer portfolio designed as a sales page for hiring managers and freelance clients. Built on the DevCore Shared Library.

Single-page application showcasing skills, projects, experience, and a live contact form. Minimal, clean design focused on conversions rather than aesthetics.

**Part of the DevCore Suite** — a collection of business-ready web applications sharing a common core library.

---

## Features

| Feature | Description |
|---------|-------------|
| Professional Design | Clean, minimal dark theme focused on business conversion |
| Project Showcase | Links to live demos of 4 featured projects from DevCore Suite |
| Skills Display | Technical skills with proficiency indicators |
| Work Models | Display availability for part-time, contract, freelance, or agency work |
| Contact Form | Live contact form with validation and logging |
| Responsive Layout | Mobile-friendly design for all devices |
| Fast Loading | Single HTML file, no build process needed |
| Customizable | Easy personalization - name, contact info, rates, hours available |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.1+ (single index.php) |
| Frontend | Vanilla JavaScript ES2022 |
| Design | CSS3 + DevCore UI library |
| Shared Core | DevCore Shared Library (git submodule at ./core/) |
| Forms | DevCore Validator + contact logging to file |

---

## Project Structure

```
portfolio/
├── index.php                   Main portfolio page (all in one file)
├── config.example.php          Configuration template
├── contact_log.txt             Contact submissions log (created automatically)
├── .env.example                Environment variables template
│
├── api/
│   └── contact.php             POST contact form (client-side JavaScript submits here)
│
└── core/                       DevCore shared library (git submodule)
    ├── bootstrap.php           Autoloader + config loader
    ├── backend/                PHP classes (Validator, etc.)
    └── ui/                     CSS framework + JavaScript utilities
```

---

## Setup Instructions

### 1. Clone DevCore Shared Library

```bash
git clone https://github.com/anshuman-dwibedi/devcore-shared.git core
```

Or using submodule:
```bash
git clone --recursive https://github.com/anshuman-dwibedi/portfolio.git
```

### 2. Configure Application

```bash
cp config.example.php config.php
```

Edit `config.php`:

```php
return [
    'db_host'    => 'localhost',
    'db_name'    => 'portfolio',  // optional: for database logging
    'db_user'    => 'root',
    'db_pass'    => '',
    'app_name'   => 'Portfolio',
    'app_url'    => 'http://localhost/portfolio',
    'debug'      => true,
    'api_secret' => 'your-secure-random-string',
];
```

### 3. Customize Your Portfolio

Edit the `$dev` configuration array at the top of `index.php`:

```php
$dev = [
    'name'       => 'Your Name',
    'title'      => 'PHP & JavaScript Developer',
    'tagline'    => 'Remote · Full-time · Contract · Freelance',
    'email'      => 'you@example.com',
    'github'     => 'github.com/yourname',
    'github_url' => 'https://github.com/yourname',
    'hours'      => '40',          // hours/week available
    'location'   => 'Remote',
];
```

### 4. Update Project Links

In `index.php`, find the Projects section and update the live demo links to your actual project URLs:

Search for these and replace with your URLs:
- `../restrodesk/index.php`
- `../estatecore/index.php`
- `../livestore/index.php`
- `../medibook/index.php`

Or point to live hosted versions:
```javascript
projects: [
    {
        name: 'RestroDesk',
        demo: 'https://restrodesk-demo.yourdomain.com',
        ...
    },
]
```

### 5. Start Web Server

Using PHP built-in server:
```bash
php -S localhost:8000
```

Or configure Apache/Nginx to point to project root.

### 6. Access Portfolio

```
http://localhost:8000/portfolio/index.php
```

---

## Configuration

### Customizing index.php

At the top of `index.php`, edit the `$dev` array to personalize your portfolio:

**Name & Contact:**
```php
'name'       => 'Your Name',
'email'      => 'you@example.com',
'github'     => 'github.com/yourusername',
'github_url' => 'https://github.com/yourusername',
```

**Availability:**
```php
'hours'      => '40',          // hours per week
'location'   => 'Remote',     // or specific location
'tagline'    => 'Full-time · Contract · Freelance',
```

**Work Rates:**
In the "About" section, find the work-type cards:
- Part-time rate
- Contract rate
- Freelance rate (hourly)
- Agency rate

Edit directly in HTML or add to config.

**Skills & Proficiency:**
Each skill card has a `data-width` attribute (0-100):
```html
<div class="skill-bar" data-width="85">
    <div class="fill"></div>
    <span>JavaScript - 85%</span>
</div>
```

Update percentage and text to reflect your actual proficiency.

**Colors & Branding:**
CSS variables in `<style>` block:
```css
:root {
    --dc-accent:      #0066ff;
    --dc-accent-2:    #0052cc;
    --dc-accent-glow: rgba(0, 102, 255, 0.25);
}
```

---

## How It Works

### Single-Page Architecture

The entire portfolio is one `index.php` file containing:
- HTML structure (sections, cards, layout)
- PHP rendering of `$dev` config values
- Inline JavaScript for interactivity
- CSS styling

This keeps deployment simple — just copy one file.

### Contact Form

When a visitor submits the contact form:

1. JavaScript validates fields client-side
2. `DC.post('api/contact.php', data)` sends JSON POST request
3. `api/contact.php` validates server-side using DevCore `Validator`
4. On success:
   - Submission appended to `contact_log.txt` (format: timestamp | name | email | message)
   - Success toast shown in browser
5. On error:
   - Error toast shows validation issues
   - Form remains for correction

**Contact Log Format:**
```
[2025-03-15 14:32:01] | Alex Smith | alex@company.com | contract | 2000-5000 | Hi, I have a project...
```

**Reading Contact Log:**
```bash
cat contact_log.txt
# or tail it live:
tail -f contact_log.txt
```

### Project Showcase

The "Featured Projects" section displays:
- Project name
- Quick description
- Live demo link
- GitHub repository link

Edit project cards in `index.php` HTML section.

---

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/contact.php | Submit contact form (validation + logging) |

---

## Troubleshooting

**"Cannot include core/bootstrap.php"**
- Clone: `git clone https://github.com/anshuman-dwibedi/devcore-shared.git core`
- Or: `git submodule update --init`

**Contact form always shows error**
- Verify `api/contact.php` is accessible: http://localhost:8000/portfolio/api/contact.php
- Check DevCore is loaded: Visit index.php and look for styling (CSS loads from core)
- Check browser console for JavaScript errors

**Contact log not created**
- Ensure portfolio folder is writable: `chmod 755 portfolio/`
- First submission auto-creates `contact_log.txt`

**Customization changes not appearing**
- Clear browser cache: Ctrl+Shift+Delete (or Cmd+Shift+Delete on Mac)
- verify changes saved in index.php
- Refresh page (Ctrl+R or Cmd+R)

**GitHub / Project links not working**
- Verify URLs are correct and accessible
- Check if external domains are reachable
- Test in incognito/private mode to rule out caching

**Contact notifications not working**
- Basic setup logs to file only
- To enable email: see "SMTP email + acknowledgement" below

### SMTP email + acknowledgement

`api/contact.php` now supports SMTP using PHPMailer, including:
- owner notification email (`CONTACT_EMAIL`)
- sender acknowledgement email ("We received your message")
- automatic fallback to file logging if SMTP fails

1. Install PHPMailer: `composer require phpmailer/phpmailer`
2. Set SMTP environment variables in `.env`:

```dotenv
CONTACT_EMAIL=you@yourdomain.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USER=your-email@gmail.com
MAIL_PASS=your-google-app-password
MAIL_FROM=your-email@gmail.com
MAIL_FROM_NAME=Portfolio
MAIL_ENCRYPTION=tls
```

3. Keep `contact_log.txt` enabled as a backup audit trail

For Gmail, use a Google App Password (not your regular account password).

---

## Environment Variables

Create `.env` or configure in config.php:

| Variable | Purpose |
|----------|---------|
| APP_NAME | Portfolio title |
| APP_URL | Public URL for links |
| DEBUG | Debug mode (true/false) |
| CONTACT_EMAIL | Owner inbox for contact notifications |
| CONTACT_RATE_LIMIT | Max submissions per IP in window (default: 5) |
| CONTACT_RATE_WINDOW | Rate-limit window in seconds (default: 600) |
| MAIL_HOST | SMTP host (for example: smtp.gmail.com) |
| MAIL_PORT | SMTP port (587 for TLS) |
| MAIL_USER | SMTP username/email |
| MAIL_PASS | SMTP app password |
| MAIL_FROM | Sender email address |
| MAIL_FROM_NAME | Sender display name |
| MAIL_ENCRYPTION | tls or ssl |

---

## License

MIT License — see LICENSE file.

---

**Questions?** Visit [DevCore Shared Library](https://github.com/anshuman-dwibedi/devcore-shared) repository.
