# Counter Dictation For Agents

## Purpose

This file summarizes counter-module dictation notes for AI agents.

The original Japanese dictation is stored in `dictation.ja.md`.

## Current Guidance

- Build the access counter without a database; store counts in text files.
- Keep save, view, and calendar display as separate entry files.
- Use English for source code, source comments, and runtime messages.
- Keep HTML out of PHP logic files; render HTML through `.phtml` templates with `OP()->Template()`.
- Store counter files under `asset/db/counter/<domain>/yyyy/mm/dd.txt`.
- Use `SERVER_NAME` for the counter domain.
- Count all accesses by default, but skip admin requests only when `OP()->Config('counter')['skip'] === 'admin'` and `OP()->isAdmin()` is true.
- Emit a `D()` debug message whenever an admin request is skipped.
- The counter module default config template is `asset/module/counter/config.php`; users copy it to `asset/config/counter.php`.
- Do not automatically load module-side `config.php`.
- Keep optional calendar-display logic out of normal `save.php` and `view.php` requests.
- Initialization guidance logic belongs in `OP\MODULE\COUNTER\InitGuidance` in `InitGuidance.class.php`; do not use the redundant `CounterInitGuidance` class or file name.
- Calendar logic belongs in `OP\MODULE\COUNTER\Calendar` in `Calendar.class.php`.
- `calendar.php` should lazy-load `Calendar.class.php` with `__DIR__ . '/Calendar.class.php'`.
- `Calendar.class.php` owns package behavior and must remain visible to CI with `OP_CI`, `CI_AllMethods()`, `ci/Calendar.php`, and method-level CI files.
- Calendar display reads only selected-month `dd.txt` files, does not increment counters, and does not scan unrelated months.
- Calendar month selection uses `OP()->Request('counter_year')` and `OP()->Request('counter_month')`; missing or out-of-range values show the current month.
- Sunday day numbers are red, Saturday day numbers are blue, and zero counts are light gray.
