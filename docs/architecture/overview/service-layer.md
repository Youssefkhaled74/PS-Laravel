# Service Layer

All business logic resides in `app/Services/`. Services are responsible for:
- Coordinating models and repositories
- Handling uploads and file moves
- Emitting events where necessary
- Returning domain results consumed by controllers and resources

Controllers should call services and return resource-wrapped responses.
