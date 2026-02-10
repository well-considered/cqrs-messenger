# cqrs-messenger

Symfony Messenger adapter for the [cqrs](https://github.com/well-considered/cqrs-messenger) semantics.

This package:
- integrates CQRS semantics with Symfony Messenger
- enforces command/query invariants at compile-time and runtime
- provides attributes and middleware for safe execution

This package does **not**:
- define CQRS semantics
- provide event sourcing
- manage transactions
- replace Symfony Messenger
