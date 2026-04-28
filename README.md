# 🌟 Daily Vision | Spiritual Lens

> "Take a photo, receive a reflection. A spiritual lens for your daily journey."

**Daily Vision** is a modern, mobile-first Progressive Web App (PWA) designed to transform everyday visual moments into spiritual insights. By combining your camera with advanced AI, it provides personalized Bible verses and devotionals based on the visual themes of your photos.

![Daily Vision Banner](public/assets/icons/icon-512.png)

## ✨ Features

- **📸 Camera-First Interface**: A minimal, full-screen mobile experience optimized for instant capture.
- **🧠 AI Analysis (Gemini)**: Dominant visual themes and moods are analyzed to return relevant scripture and reflections.
- **🎨 Dynamic Canvas Art**: Every reflection is unique, with AI-suggested "Vibe Colors," typography, and custom text overlays generated on-the-fly.
- **🔄 Reimagine Function**: One click triggers a high-temperature AI "Reimagine" for a completely different spiritual perspective.
- **🌍 Community Gallery**: Share your visions to a public community feed where others can find inspiration.
- **🔗 Persistent Sharing**: Generates unique, shareable URLs for every vision with dynamic SEO previews for social media.
- **💼 Boss Panel**: A secure, professional admin dashboard to manage AI settings and moderate the community gallery.
- **📱 PWA Excellence**: Fully installable on iOS and Android with offline caching, auto-updating, and a native app feel.

## 🛠️ Tech Stack

- **Frontend**: Vanilla JS (State-based), Tailwind CSS, HTML5 Canvas.
- **Backend**: Vanilla PHP 8.2+ (Custom MVC Architecture).
- **Database**: SQLite 3 (Settings & Vision persistence).
- **AI Engine**: Google Gemini API.
- **PWA**: Manifest.json & Service Workers with auto-cache busting.

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher.
- SQLite enabled.
- Composer.

### Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/funnyoldmonkey/dailyvision.git
   cd dailyvision
   ```

2. **Install dependencies**:
   ```bash
   composer dump-autoload
   ```

3. **Database Configuration**:
   The database comes pre-configured. To manage your API keys and settings, log in to the **Boss Panel**.

4. **Boss Panel (Admin)**:
   Access the admin dashboard at `/boss` to update your Gemini API Key and AI Model.
   - **Secret Question**: *"What's your nickname when you were a kid?"*
   - **Default Answer**: `badang` (Change this in the database for production).

5. **Run Locally**:
   Point your local web server (like XAMPP) to the project root. The `.htaccess` files will automatically route requests to the `public/` directory.

## 📁 Project Structure

```text
/app
  /Controllers  - Request handlers (Ai, Vision, Admin)
  /Core         - Router, Database, Session, Helpers
  /Models       - Data models
  /Views        - HTML templates (Admin, Gallery, Vision View)
/public
  /assets       - CSS, JS, and Icons
  /visions      - Physical storage for shared images
  /index.php    - App entry point
/database       - SQLite file location
```

## 📜 License
Built with ❤️ by **Jall Fiel**. Free to use for spiritual and educational purposes.

---
*May every photo you take lead to a moment of reflection.*
