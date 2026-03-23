# Developer Portfolio
### Part of the DevCore Portfolio Suite

A production-grade personal developer portfolio â€” single `index.php` file â€”
built on the DevCore shared library. Every section answers the question a
hiring manager or client is silently asking: **"Can this person solve my problem?"**

---

## What this is

A dark, technical portfolio page designed to function as a **sales page**, not a
university assignment. It targets hiring managers, agency owners, and freelance
clients simultaneously by surfacing four engagement models (part-time, contract,
freelance, agency) with specific selling points per model.

The DevCore Suite is the proof. The product is: *a reliable, available developer
who can ship real business tools fast.*

---

## Where to place it

```
devcore-suite-clean/
  core/
    bootstrap.php
    ui/
      devcore.css
      devcore.js
      parts/
        _icons.css
        ...
  projects/
    portfolio/          â† place this folder here
      index.php
      api/
        contact.php
      contact_log.txt   â† created automatically on first submission
      README.md
    restaurant-qr-ordering/
    real-estate-listings/
    ecommerce-live-store/
    medical-booking-system/
```

The portfolio expects the core library at `./core/` relative to `index.php`.

---

## Quick start

1. Copy this `portfolio/` folder into `devcore-suite-clean/projects/`
2. Open `index.php` and edit the `$dev` config block at the top:

```php
$dev = [
    'name'       => 'Your Name',
    'title'      => 'PHP & JS Developer',
    'tagline'    => 'Remote Â· Part-time Â· Contract Â· Freelance',
    'email'      => 'you@example.com',
    'github'     => 'github.com/yourusername',
    'github_url' => 'https://github.com/yourusername',
    'hours'      => '20',      // hours/week available
    'location'   => 'Remote',
];
```

3. Update the Live Demo links in the Projects section to match your actual project URLs.
4. Serve via PHP (Apache / Nginx / `php -S localhost:8000`).

---

## How to customise

### Name, email, GitHub
Edit the `$dev` array at the top of `index.php`. All occurrences are generated
from that single block â€” no find-and-replace needed.

### Work types and rates
The four work-type cards in the About section and the contact form select options
are hardcoded in the HTML. Edit the text directly in the relevant sections.

### Available hours
Change `$dev['hours']` to reflect your current availability (e.g. `'20'` for 20 hrs/week).
This appears in the Part-time work card.

### Project links
Search for `../restaurant-qr-ordering/index.php` etc. in `index.php` and update to
your actual live demo URLs. For GitHub links, update `$dev['github_url']` â€” the
project card links append `/project-name` to that base URL.

### Skills and proficiency bars
Each skill card has a `data-width` attribute on the fill bar element (e.g. `data-width="90"`).
Update the percentage value and the visible text to reflect your actual skill level.

### Colours / accent
The design system tokens live in `./core/ui/parts/_tokens.css`.
Override per-project by adding CSS variables to the `<style>` block in `index.php`:

```css
:root {
    --dc-accent:      #your-color;
    --dc-accent-2:    #your-lighter-color;
    --dc-accent-glow: rgba(r,g,b,0.25);
}
```

---

## Contact form

### How it works

1. User fills in the form and clicks Send.
2. JavaScript validates the fields client-side.
3. `DC.post('api/contact.php', data)` sends a JSON POST request.
4. `api/contact.php` validates server-side with `Validator::make()`.
5. On success, the submission is appended to `contact_log.txt` in this format:

```
[2025-03-15 14:32:01] | Alex Smith | alex@company.com | contract | 2000-5000 | Hi, I have a project...
--------------------------------------------------------------------------------
```

6. A success toast appears in the browser.

### Reading your contact log

```bash
cat projects/portfolio/contact_log.txt
```

Or tail it live:
```bash
tail -f projects/portfolio/contact_log.txt
```

### Swap to SMTP email

The `api/contact.php` file includes a commented-out PHPMailer block. To enable:

1. Install PHPMailer: `composer require phpmailer/phpmailer`
2. Uncomment the SMTP block in `api/contact.php`
3. Fill in your SMTP credentials
4. Optionally keep the file log as a backup

You can also replace the log with a database insert using `Database::getInstance()` â€”
the bootstrap is already loaded.

---

## Features

| Feature | Implementation |
|---|---|
| Scroll-reveal animations | Custom `IntersectionObserver` + `.reveal` / `.revealed` classes |
| Skill bar animations | `data-width` + CSS transition triggered on scroll entry |
| Hero stat counters | `animateCount()` from `devcore.js` via `data-count` |
| Navbar scrollspy | `IntersectionObserver` on `section[id]` elements |
| Mobile nav drawer | Hamburger button toggles `.open` on `.mobile-nav` |
| Spinning code window border | CSS `@property --border-angle` + `conic-gradient` |
| Hero stagger animation | `.dc-stagger` class from `devcore.css` |
| Contact form | `DC.post()` + `Toast.success/error()` from `devcore.js` |
| Server validation | `Validator::make()` from DevCore core |

---

## Part of the DevCore Portfolio Suite

```
devcore-suite-clean/
  core/               â† shared library (Database, Api, Auth, Storage...)
  projects/
    portfolio/        â† this file
    restaurant-qr-ordering/
    real-estate-listings/
    ecommerce-live-store/
    medical-booking-system/
```

All five projects share one core library. The portfolio is the proof.

