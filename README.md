# Developer Portfolio

A production-grade personal developer portfolio designed as a sales page for hiring managers and freelance clients. Built on the DevCore Shared Library.

Single-page application showcasing skills, projects, experience, and a live contact form. Minimal, clean design focused on conversions rather than aesthetics.

**Part of the DevCore Suite** â€” a collection of business-ready web applications sharing a common core library.

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
â”œâ”€â”€ index.php                   Main portfolio page (all in one file)
â”œâ”€â”€ config.example.php          Configuration template
â”œâ”€â”€ contact_log.txt             Contact submissions log (created automatically)
â”œâ”€â”€ .env.example                Environment variables template
â”‚
â”œâ”€â”€ api/
â”‚   â””â”€â”€ contact.php             POST contact form (client-side JavaScript submits here)
â”‚
â””â”€â”€ core/                       DevCore shared library (git submodule)
    â”œâ”€â”€ bootstrap.php           Autoloader + config loader
    â”œâ”€â”€ backend/                PHP classes (Validator, etc.)
    â””â”€â”€ ui/                     CSS framework + JavaScript utilities
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
    'tagline'    => 'Remote Â· Full-time Â· Contract Â· Freelance',
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
'tagline'    => 'Full-time Â· Contract Â· Freelance',
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

This keeps deployment simple â€” just copy one file.

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
- To enable email: See "Swap to SMTP email" section below

---

## Advanced: Email Notifications

By default, contact submissions are logged to `contact_log.txt`. To send email notifications instead:

### Option 1: Install PHPMailer

```bash
composer require phpmailer/phpmailer
```

### Option 2: Uncomment SMTP Block

In `api/contact.php`, find the PHPMailer configuration (currently commented) and uncomment:

```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';         // Your SMTP server
$mail->SMTPAuth   = true;
$mail->Username   = 'your-email@gmail.com';  // Your email
$mail->Password   = 'app-password';          // App-specific password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;

$mail->setFrom('your-email@gmail.com', 'Portfolio Contact');
$mail->addAddress('your-email@gmail.com');  // Where to send notifications
$mail->Subject = "New Contact Form Submission";
$mail->Body = $message;

if ($mail->send()) {
    echo json_encode(['status' => 'success', 'message' => 'Message sent!']);
}
```

### Where to Configure

- `Host` â€” Your SMTP server (Gmail, SendGrid, etc.)
- `Username` â€” Your email address
- `Password` â€” App-specific password (for Gmail, use app passwords)
- `addAddress()` â€” Where notifications are sent

---

## Environment Variables

Create `.env` or configure in config.php:

| Variable | Purpose |
|----------|---------|
| APP_NAME | Portfolio title |
| APP_URL | Public URL for links |
| DEBUG | Debug mode (true/false) |
| CONTACT_LOG_PATH | Where to save contact submissions (default: ./contact_log.txt) |
| CONTACT_EMAIL | Email to notify on submissions (for SMTP setup) |
| SMTP_HOST | SMTP server for email (advanced) |
| SMTP_USER | SMTP username (advanced) |
| SMTP_PASS | SMTP password (advanced) |

---

## License

MIT License â€” see LICENSE file.

---

**Questions?** Visit [DevCore Shared Library](https://github.com/anshuman-dwibedi/devcore-shared) repository.

