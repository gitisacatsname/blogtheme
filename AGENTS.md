# AGENTS

## Theme asset URLs

When enqueueing or localizing theme assets, always derive paths and URLs using WordPress APIs such as `get_theme_file_uri()` and `get_theme_file_path()`. Avoid manual string concatenation or hard-coding hosts, ports, or `page/assets` prefixes. Any enqueue logic should be covered by unit tests that assert the correct use of these APIs.
