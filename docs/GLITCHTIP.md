# GlitchTip / Sentry

Centralized error tracking is not currently installed. Laravel still logs errors normally; production should collect application stderr or daily log files.

To adopt GlitchTip or Sentry:

1. Install the current official Laravel SDK through Composer.
2. Follow its Laravel-version-specific exception-handler integration.
3. Store the DSN only in the production secret store.
4. Set environment and release to the deployment environment and commit SHA.
5. Begin with a low tracing sample rate and profiling disabled.
6. Send a deliberate test exception from staging and verify alert routing.
7. Add an SDK integration test and an operations owner before describing monitoring as active.

Avoid sending request bodies, authentication headers, cookies, or personal data unless the privacy implications have been reviewed.
