# cqrs

This package defines the **semantic core** of CQRS:
what commands and queries *mean*, independent of
frameworks, transports, or execution models.

It intentionally provides:
- marker interfaces for intent
- handler contracts
- semantic violation types

It intentionally does **not** provide:
- message buses
- dispatching
- async behavior
- framework integration

Execution is provided by adapters (e.g. cqrs-messenger).
