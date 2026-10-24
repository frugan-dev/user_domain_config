# Security Policy

## Supported Versions

Only the latest release is maintained: fixes are not backported.

## Reporting a Vulnerability

We take security seriously. If you discover a security vulnerability, please report it responsibly.

### How to Report

**Please DO NOT file a public issue for security vulnerabilities.**

Instead, use [private vulnerability reporting](https://github.com/frugan-dev/user_domain_config/security)
or send an email to: **<dev@frugan.it>**

Include the following information:

- Description of the vulnerability
- Steps to reproduce the issue
- Potential impact assessment
- Any suggested fixes (if available)

Vulnerabilities of Roundcube itself go to the
[Roundcube security team](https://roundcube.net/support/).

### What to Expect

1. **Acknowledgment**: We will acknowledge receipt within 48 hours
2. **Assessment**: We will assess the vulnerability within 5 business days
3. **Resolution**: We will work to resolve critical issues within 30 days
4. **Disclosure**: We will coordinate with you on responsible disclosure

### Responsible Disclosure

We believe in responsible disclosure. We ask that you:

- Give us reasonable time to investigate and fix the issue
- Do not publicly disclose the vulnerability until we've had a chance to fix it
- Do not exploit the vulnerability for malicious purposes

### Security Best Practices

When using this plugin:

1. **Trusted files only**: the per-domain files are PHP included by
   Roundcube. Keep them, and the directories they are loaded from, writable
   only by the administrator
2. **Not in the image's config directory**: with the official Docker image,
   keep them out of `/var/roundcube/config`, whose files apply to every user
3. **Loaded before authentication**: a login attempt with an address of a
   domain loads that domain's file, whatever the password. Settings that only
   the domain's own users may see do not belong there

## Security Contact

For security-related questions or concerns:

- Email: <dev@frugan.it>
- GitHub: [@frugan-dev](https://github.com/frugan-dev)

## Acknowledgments

We appreciate the security research community's efforts in responsibly disclosing vulnerabilities. Security researchers who help us improve this project will be acknowledged in our security advisories (with their permission).
