# Arkesel SDKs

Client SDKs for the [Arkesel](https://arkesel.com) SMS/OTP gateway.

| Language | Package | Registry | Docs |
|---|---|---|---|
| PHP | `kalourmade/arkesel-sms` | [Packagist](https://packagist.org/packages/kalourmade/arkesel-sms) | [docs/php.md](docs/php.md) |
| Python | `kalourmade-arkesel` | [PyPI](https://pypi.org/project/kalourmade-arkesel/) | [docs/python.md](docs/python.md) |
| Node | `@kalourmade/arkesel-sms` | [npm](https://www.npmjs.com/package/@kalourmade/arkesel-sms) | [docs/node.md](docs/node.md) |

Each SDK covers: sending SMS (single or batch recipients), checking
message status, contact groups, balance, and OTP generate/verify. See
the per-language guide for install + quickstart.

## Status

v1 covers SMS, Contact Groups, Balance, and OTP. See
[docs/ROADMAP.md](docs/ROADMAP.md) for what's planned next (Voice SMS
and a browser "click to record" demo).

## Releasing

All three packages are released together from a single tag. Pushing a
tag like `v0.1.0` triggers `.github/workflows/release.yml`, which
reads the version from the tag itself and publishes all three
packages at that version in one run:

```bash
git tag v0.1.0
git push origin v0.1.0
```
