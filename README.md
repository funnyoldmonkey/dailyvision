# 🌟 Daily Vision | Spiritual Lens

> "Take a photo, receive a reflection. A spiritual lens for your daily journey."

**Daily Vision** is a modern, mobile-first Progressive Web App (PWA) designed to transform everyday visual moments into spiritual insights. By combining your camera with advanced AI, it provides personalized Bible verses and devotionals based on the visual themes of your photos.

![Daily Vision Banner](public/assets/icons/icon-512.png)

## ✨ Features

- **📸 Camera-First Interface**: A minimal, full-screen mobile experience optimized for instant capture.
- **🧠 AI Analysis (Gemini)**: Dominant visual themes and moods are analyzed to return relevant scripture and reflections.
- **🎨 Dynamic Canvas Art**: Every reflection is unique, with AI-suggested "Vibe Colors," typography, and custom text overlays generated on-the-fly.
- **🔄 Reimagine Function**: Not satisfied? One click triggers a high-temperature AI "Reimagine" for a completely different spiritual perspective.
- **📱 PWA Excellence**: Fully installable on iOS and Android with offline caching and a native app feel.
- **📲 Premium Sharing**: Share your generated "Blessing Image" directly to Facebook, Messenger, and more with formatted captions.

## 🛠️ Tech Stack

- **Frontend**: React-like state management with Vanilla JS, Tailwind CSS, and HTML5 Canvas.
- **Backend**: Vanilla PHP 8.2+ (Custom MVC Architecture).
- **Database**: SQLite 3 (Secure storage for API configuration).
- **AI Engine**: Google Gemini API.
- **PWA**: Manifest.json & Service Workers.

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

3. **Initialize the Database**:
   Run the setup script to create the SQLite database and seed the API settings:
   ```bash
   php app/Core/Setup.php
   ```

4. **Configure your API Key**:
   The `app/Core/Setup.php` script contains a placeholder Gemini API key. For production, update your key in the `settings` table of `database/dailyvision.sqlite`.

5. **Run Locally**:
   Point your local web server (like XAMPP) to the project root. The `.htaccess` files will automatically route requests to the `public/` directory.

## 📁 Project Structure

```text
/app
  /Controllers  - Request handlers
  /Core         - Router, Database, Session, Helpers
  /Models       - Data models
  /Views        - HTML templates
/public
  /assets       - CSS, JS, and Icons
  /index.php    - App entry point
/database       - SQLite file location
```

## 📜 License
Built with ❤️ by **Jall Fiel**. Free to use for spiritual and educational purposes.

---
*May every photo you take lead to a moment of reflection.*
