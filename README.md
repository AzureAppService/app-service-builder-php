# App Service Builder: PHP sample

A dependency-free PHP 8.5 web app for learning the App Service Builder
GitHub-source workflow. The app, Composer project, and `builder.yaml` are at
the repository root; no other sample repository or external service is needed.
The home page at `/` is an editorial-style PHP showcase displaying the public
greeting and actual PHP runtime version. Health remains a plain-text endpoint.

## Preview

![PHP showcase with a greeting, the actual runtime version, and a terracotta editorial layout](https://github.com/user-attachments/assets/bbf1ac67-2e6c-40a5-96ae-9dcc4fbed7b7)

The screenshot shows the PHP showcase running locally in Microsoft Edge.
Hosted root routing remains unqualified; see
[Open the visual showcase](#open-the-visual-showcase).

**App Service Builder is in Private Preview. Use this sample for development
and testing, not production.** Local checks do not qualify the hosted build,
nginx/PHP-FPM startup, or source authorization. Live qualification of this
sample is pending. Use a subscription and location enabled for your preview
access; this sample does not promise availability in any region.

## Prerequisites

- PHP **8.5.x**, Composer **2.x**, and Git.
- Python **3.10+** for the dependency-free local test runner; it is not an app
  runtime dependency. CI uses Python 3.14.
- For deployment, Azure preview access, an authorized subscription and resource
  group, and a GitHub account that can fork and authorize the source repository.

## Run locally and test

Fork [AzureAppService/app-service-builder-php](https://github.com/AzureAppService/app-service-builder-php/fork)
into your GitHub account, then replace `<your-github-account>`:

```sh
git clone https://github.com/<your-github-account>/app-service-builder-php.git
cd app-service-builder-php
composer validate --strict --no-check-publish
php -l index.php
php -l health.php
python3 -m unittest discover -s tests -v
php -S 127.0.0.1:0 -t .
```

The Composer project requires `>=8.5 <8.6` and has no third-party packages.
No dependency installation is needed to run the app locally. Keep
`composer.json` and `composer.lock` together for platform build detection.

The tests start and stop their own PHP development servers on OS-assigned
loopback ports. They check exact HTTP bodies/statuses, greeting configuration,
and the source contract, plus root HTML, removed-route 404s, and HTML escaping.
All tests are Python files under `tests/`; the sample's PHP entry points are
`index.php` and `health.php`. If PHP 8.5 is not the default
`php` on your PATH, run the tests with `PHP_BIN=/path/to/php`.

The final command starts a **local development fixture only**, not the hosted
server. PHP prints its assigned URL, for example `http://127.0.0.1:49152`.
Open that URL in a browser, then append `/health.php` to check health. The
port is assigned at startup; do not assume the example port. With curl
installed, replace `<local-url>` with the printed URL:

```sh
curl -i "<local-url>/"
curl -i "<local-url>/health.php"
```

| Path | Status | Response |
| --- | --- | --- |
| `/` or `/index.php` | 200 | HTML showcase with the greeting and PHP runtime version, served directly without a redirect. |
| `/health.php` | 200 | Plain text: `healthy` |
| `/not-found` | 404 | Not found (body may differ between local and hosted servers) |

The page displays the actual PHP patch version. The health body is exactly
`healthy\n`. Query strings do not change either response. Stop the development
server with **Ctrl+C**.

### Open the visual showcase

Open `<local-url>/` for the responsive, terracotta PHP showcase used for
template previews. It renders the real `GREETING` value and PHP runtime version,
with links to the health endpoint, source, and
quickstart. Greeting text is HTML-escaped; it cannot add markup or scripts.
There are no fonts, scripts, images, or packages to download at runtime.

The showcase is the root `index.php`; `/index.php` also works locally. It needs
no router, rewrite, custom build command, or startup override. It relies on
standard directory-index handling and returns `text/html; charset=utf-8`.
Hosted root routing remains unqualified until a live deployment is checked.
If the hosted server does not resolve `/`, check `/index.php` and the server
logs rather than replacing nginx/PHP-FPM.

There is no separate showcase route or plain-text greeting endpoint. The
showcase does not claim that a hosted deployment or health check has succeeded.

## Deploy your fork with Portal

See the [App Service Builder documentation](https://builder.appservice.azure.com/docs/)
and [Portal quickstart](https://builder.appservice.azure.com/docs/quickstarts/portal/)
for current preview onboarding and deployment steps.

1. Open [App Service Builder Portal](https://builderapps.azure.com/) and sign in
   with the account enabled for your preview. Create an app in your authorized
   subscription, resource group, and preview-enabled location.
2. Authorize GitHub source access to **your fork**, including organization
   access if applicable. Use Portal's authorization flow; do not put tokens or
   credentials in source files.
3. Select `<your-github-account>/app-service-builder-php`, branch **`main`**,
   and the **repository root** as the source directory. `builder.yaml`,
   `composer.json`, `composer.lock`, `index.php`, and `health.php` must all be
   at that root. Do not select `tests/` or a `samples/php-web` subdirectory.
4. Start deployment and wait for successful completion in Portal. A saved
   source connection alone does not establish that the app is running.
5. Copy the runtime URL shown in Portal. Replace `<app-runtime-url>` below
   with that URL, without a trailing slash, and check both endpoints:

```sh
curl -i "<app-runtime-url>/"
curl -i "<app-runtime-url>/health.php"
```

Both should return HTTP **200**, with the greeting showing the hosted PHP
version and the health body exactly `healthy\n`. If deployment fails, inspect
Portal's deployment status and logs before trying again.

### Hosted build and startup contract

`builder.yaml` declares one web component: platform `php`, version `"8.5"`,
and `run.healthCheckPath: /health.php`. It omits the single-component defaults
for `path`, `port`, and `healthCheckStatusCodes`.

The hosted runtime image's existing **nginx + PHP-FPM** stack owns startup and
the HTTP listener. **There is deliberately no `run.start`**: do not add
`php -S`, stop nginx, or add startup workarounds. The root Composer project
supports the platform's Oryx detection/build path without runtime packages.
There is no custom build command or output directory.
Keep the showcase's `index.php` and `health.php` at the source root when packaging.

The repository/source root is a **filesystem directory**. The component's
default public mount `/` is a **URL path**, not a directory to select in
Portal. Keep `index.php` and `health.php` at the packaged document root; PHP
files nested under another source folder are not equivalent. See
[runtime requirements](https://builder.appservice.azure.com/docs/reference/runtimes/)
for the preview contract.

The GitHub Actions workflow validates syntax, Composer metadata, and the
local development fixture only. It does **not** deploy or qualify hosted
nginx/PHP-FPM.

## Change the public greeting

`GREETING` is deliberately **public text**, not a secret. An unset or empty
value uses `Hello`; other values, including `"0"` and whitespace, are
preserved. Only this variable is reflected in the response. For local
development, stop the previous server and run:

```sh
GREETING="Welcome" php -S 127.0.0.1:0 -t .
```

Check the newly printed URL. The HTML page should display `Welcome` and the
actual PHP version; the greeting is escaped before rendering.
`/health.php` remains exactly `healthy\n`.

For a deployed app, add `GREETING` in Portal **Variables**. **Save only**
persists desired values without changing the running version; **Save and
deploy** captures them in a new version. If you chose Save only, explicitly
deploy a new version before checking the greeting. Activating a retained
version uses its historical values. Do not override reserved `PORT`.
See the [variables guide](https://builder.appservice.azure.com/docs/guides/variables/).

## Push updates and explicitly redeploy

Make changes in your fork, rerun the local checks above, and get the changes
onto its configured `main` branch, for example by merging your own pull
request. Push to GitHub before redeploying; uncommitted or unpushed local
changes are not uploaded.

In Portal, explicitly deploy from the configured fork and `main` branch,
wait for successful completion, then check `/` and `/health.php` on the
runtime URL again. Pushing source does not by itself confirm deployment.

## Cleanup

Stop local servers with **Ctrl+C**. In Portal, delete **only the sample app**,
confirm the deletion, and wait for completion. Do not delete a shared resource
group, subscription, fork, or unrelated resources. App deletion does not
remove your GitHub repository or separately created resources.

## Contributing, security, and license

See [CONTRIBUTING.md](CONTRIBUTING.md) for contribution and CLA guidance, and
the [Microsoft Open Source Code of Conduct](.github/CODE_OF_CONDUCT.md).
Report security concerns through
[Microsoft's security reporting guidance](https://www.microsoft.com/msrc)
rather than a public issue. This sample is licensed under [MIT](LICENSE.md).

## Resources

- [App Service Builder documentation](https://builder.appservice.azure.com/docs/)
- [Portal quickstart](https://builder.appservice.azure.com/docs/quickstarts/portal/)
- [Samples catalog](https://builder.appservice.azure.com/docs/samples/)
- [Runtime requirements](https://builder.appservice.azure.com/docs/reference/runtimes/)
