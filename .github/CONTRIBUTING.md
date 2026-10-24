# Contributing to user_domain_config

Thank you for your interest in contributing!

## Repository Structure

```text
├── user_domain_config.php      # The plugin
├── composer.json               # Package metadata, Pint scripts
├── pint.json                   # Formatting rules
├── docker-compose.yml          # Local stack: Roundcube + GreenMail, Composer
├── tests/
│   ├── smoke.sh                # End-to-end check through the login form
│   └── fixtures/
│       ├── domains/            # Per-domain files loaded by the plugin
│       └── outside/            # Target of the path traversal checks
├── .github/
│   └── workflows/
│       ├── ci.yml              # Pint, syntax on the oldest and newest PHP, smoke test
│       ├── release.yml         # CI, then semantic release
│       ├── commitlint.yml      # Commit message linting on PRs
│       ├── labeler.yml         # Auto-label PRs
│       ├── auto-merge.yml      # Auto-merge Dependabot PRs
│       └── stale.yml           # Stale issue/PR housekeeping
└── .releaserc.json             # Semantic release configuration
```

## How to Contribute

### Reporting Issues

When reporting:

- Specify the plugin version, the Roundcube version and how it is installed
- Include the PHP version
- Say where the per-domain files are and how they are named, and remove
  every secret from what you paste

### Proposing Changes

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Test locally (see below)
5. Submit a pull request

## Testing Locally

```bash
docker compose up -d --wait
tests/smoke.sh
docker compose run --rm composer install
docker compose run --rm composer lint
```

A change to the plugin's behaviour comes with a check in `tests/smoke.sh`.
Test on another Roundcube version with
`ROUNDCUBE_TAG=1.6.x-apache docker compose up -d --wait`, after a
`docker compose down`.

## Code Style

- Comments and any other text inside the code are in English
- Explain *why*, not *what*
- PHP 7.1 syntax and the Roundcube 1.5 API: newer syntax or methods raise the
  requirements in `composer.json`, which is a breaking change
- Pint must pass (`composer lint`); the shell script must pass shellcheck

## Commit Messages

Follow [Conventional Commits](https://www.conventionalcommits.org). Allowed
types are the conventional ones plus `deps`:

```bash
feat: load a per-domain file for aliases too
fix: ignore usernames without a domain
docs(readme): document RCUBE_CONFIG_PATH
ci: bump actions/checkout to v8
chore: tidy up the compose file
```

Commit types drive the release: `feat` bumps the minor version, `fix`,
`perf`, `refactor`, `deps` and `docs(readme)` bump the patch version, and a
`BREAKING CHANGE` footer bumps the major version. `ci`, `test`, `build`,
`chore` and `style` release nothing.

## License

By contributing, you agree your contributions will be licensed under the GNU
GPLv3 or later.
