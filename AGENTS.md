# Guidelines for blogtheme

- When enqueuing assets, always use `get_theme_file_uri()` and `get_theme_file_path()` instead of manual URL concatenation. Avoid hard-coded hosts, ports, or `/page/` segments.
- Before committing, run `vendor/bin/phpunit` to execute the unit test suite.
