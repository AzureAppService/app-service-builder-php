<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (!in_array($path, ['/', '/index.php'], true)) {
    header('Content-Type: text/plain; charset=utf-8');
    http_response_code(404);
    echo "Not found\n";
    return;
}

$greeting = getenv('GREETING');
if ($greeting === false || $greeting === '') {
    $greeting = 'Hello';
}
$escapedGreeting = htmlspecialchars($greeting, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$escapedVersion = htmlspecialchars(PHP_VERSION, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

header('Content-Type: text/html; charset=utf-8');
http_response_code(200);
?>
<!doctype html>
<html lang="en">
<!--
THESIS: A small PHP program presented as a confident editorial specimen, not a dashboard.
OWN-WORLD: Paper, ink, terracotta; oversized serif lettering, hairline rules, square controls.
STORY: Read the actual greeting and PHP version, inspect the response, then explore the source.
FIRST VIEWPORT: Greeting at left, a large terracotta PHP specimen at right, real response beneath.
FORM: Brief-pinned editorial spread; stacked on mobile. No simulated features or status claims.
-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#a8402d">
    <meta name="description" content="A dependency-free PHP sample for the App Service Builder GitHub-source workflow.">
    <title>PHP / App Service Builder</title>
    <style>
        .health-button { font: inherit; color: inherit; background: transparent; border: 1px solid currentColor; padding: 10px 14px; cursor: pointer; }
        .health-button:focus-visible { outline: 3px solid #a8402d; outline-offset: 5px; }
        .health-button:disabled { cursor: wait; opacity: .7; }
        #health-status { font-size: 14px; line-height: 1.6; overflow-wrap: anywhere; }
        :root {
            color-scheme: light;
            --paper: #f6f2e9;
            --ink: #30251f;
            --muted: #6b5d53;
            --clay: #a8402d;
            --line: #d7cec0;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: Georgia, 'Times New Roman', serif; }
        a { color: inherit; text-underline-offset: 5px; }
        a:hover { text-decoration-thickness: 2px; }
        a:focus-visible { outline: 3px solid var(--clay); outline-offset: 6px; }
        .skip { position: absolute; left: 24px; top: -100px; padding: 12px; background: var(--paper); z-index: 1; }
        .skip:focus { top: 16px; }
        .page { max-width: 1440px; margin: auto; padding: 0 6%; }
        .sans { font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        header { min-height: 100px; display: flex; align-items: center; justify-content: space-between; gap: 24px; border-bottom: 1px solid var(--line); }
        .brand { display: flex; align-items: center; gap: 14px; font-size: 14px; font-weight: 600; }
        .brand-logo { width: 32px; height: 32px; flex-shrink: 0; }
        nav { display: flex; gap: 28px; font-size: 13px; }
        nav a { text-decoration: none; padding: 12px 0; }
        nav a:hover { text-decoration: underline; }
        .hero { display: grid; grid-template-columns: 1.1fr 1fr; gap: 7%; align-items: center; padding: 64px 0; }
        h1 { font-size: clamp(54px, 6.6vw, 94px); line-height: 1.04; font-weight: 400; letter-spacing: -.04em; margin: 0 0 28px; }
        .greeting { display: block; white-space: pre-wrap; overflow-wrap: anywhere; }
        h1 em { color: var(--clay); font-weight: 400; }
        .description { max-width: 400px; font-size: 16px; line-height: 1.75; color: var(--muted); margin: 0 0 30px; }
        .primary { display: inline-flex; gap: 26px; align-items: center; min-height: 48px; background: var(--ink); color: var(--paper); padding: 16px 22px; text-decoration: none; font-size: 13px; }
        .primary:hover { background: var(--clay); }
        .runtime { min-width: 0; background: var(--clay); color: var(--paper); padding: 28px 32px; }
        .runtime-top, .runtime-bottom { display: flex; justify-content: space-between; gap: 12px; align-items: center; }
        .runtime-top { padding-bottom: 18px; border-bottom: 1px solid #ffffff60; font-size: 13px; }
        .php-word { font-size: clamp(110px, 15vw, 215px); font-weight: bold; font-style: italic; letter-spacing: -.04em; line-height: 1.15; text-align: center; padding: 30px 0 38px; }
        .runtime-bottom { border-top: 1px solid #ffffff60; padding-top: 20px; font-size: 12px; }
        .runtime-version { font: 20px Georgia, serif; }
        .response { border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding: 23px 0; display: grid; grid-template-columns: 160px minmax(0, 1fr) auto; gap: 20px; align-items: start; }
        .response h2 { margin: 3px 0 0; font-size: 12px; font-weight: 500; color: var(--muted); }
        pre { margin: 0; font: 13px/1.7 ui-monospace, SFMono-Regular, Consolas, monospace; white-space: pre-wrap; overflow-wrap: anywhere; }
        .response a { font-size: 12px; line-height: 1.8; }
        .details { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 40px; padding: 38px 0 42px; }
        .details h2 { margin: 0 0 12px; font-size: 24px; font-weight: 400; letter-spacing: -.02em; }
        .details p { margin: 0; max-width: 320px; color: var(--muted); font-size: 13px; line-height: 1.75; }
        code { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; font-size: .95em; }
        footer { display: flex; justify-content: space-between; gap: 24px; border-top: 1px solid var(--line); padding: 22px 0 28px; font-size: 11px; color: var(--muted); }
        footer p { margin: 0; }
        @media (max-width: 760px) {
            .page { padding: 0 7%; }
            header { min-height: 84px; gap: 16px; }
            .brand { gap: 10px; font-size: 12px; }
            nav { gap: 16px; font-size: 12px; }
            .hero { grid-template-columns: 1fr; gap: 36px; padding: 40px 0; }
            h1 { font-size: clamp(54px, 12vw, 80px); }
            .description { max-width: 100%; }
            .runtime { padding: 24px; }
            .php-word { font-size: clamp(130px, 34vw, 215px); padding: 12px 0 24px; }
            .response { grid-template-columns: 1fr; gap: 12px; }
            .response a { justify-self: start; }
            .details { grid-template-columns: 1fr; gap: 28px; padding: 30px 0; }
            .details p { max-width: 100%; }
            footer { flex-direction: column; gap: 10px; line-height: 1.6; }
        }
        @media (max-width: 380px) {
            .brand { max-width: 165px; }
            nav { gap: 12px; }
            .brand-logo { flex-shrink: 0; }
        }
    </style>
</head>
<body>
    <a class="skip sans" href="#main">Skip to content</a>
    <div class="page">
        <header class="sans">
            <div class="brand"><svg class="brand-logo" aria-hidden="true" focusable="false" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<g clip-path="url(#clip0_4575_347)">
<path d="M17.62 8.22L7.62 0.22C7.45 0.08 7.24 0 7 0C6.45 0 6 0.45 6 1C6 1.19 6.05 1.36 6.14 1.51L10.58 9L6.14 16.49C6.05 16.64 6 16.81 6 17C6 17.55 6.45 18 7 18C7.24 18 7.45 17.92 7.62 17.78L12.14 14.17L13.16 13.35L17.62 9.78C17.85 9.6 18 9.32 18 9C18 8.68 17.85 8.4 17.62 8.22Z" fill="url(#paint0_linear_4575_347)"/>
<path d="M17.62 8.22L7.62 0.22C7.45 0.08 7.24 0 7 0C6.45 0 6 0.45 6 1C6 1.19 6.05 1.36 6.14 1.51L10.58 9L6.14 16.49C6.05 16.64 6 16.81 6 17C6 17.55 6.45 18 7 18C7.24 18 7.45 17.92 7.62 17.78L12.14 14.17L13.16 13.35L17.62 9.78C17.85 9.6 18 9.32 18 9C18 8.68 17.85 8.4 17.62 8.22Z" fill="url(#paint1_radial_4575_347)" fill-opacity="0.5"/>
<path d="M18 9C18 9.32 17.85 9.6 17.62 9.78L13.16 13.35L12.14 14.17C12.73 13.7 12.88 12.87 12.5 12.23L10.58 9L6.14 1.51C6.05 1.36 6 1.19 6 1C6 0.45 6.45 0 7 0C7.24 0 7.45 0.08 7.62 0.22L17.62 8.22C17.85 8.4 18 8.68 18 9Z" fill="url(#paint2_linear_4575_347)"/>
<path d="M3.625 16C3.98496 16 4.30512 16.19 4.48511 16.49C4.72506 16.88 4.66492 17.38 4.34497 17.7C4.145 17.8999 3.89495 18 3.625 18H1.00002C0.830074 18 0.659995 17.9601 0.510033 17.8701C0.110033 17.6401 -0.0799476 17.18 0.0300524 16.74C0.150055 16.31 0.550058 16 1.00002 16H3.625Z" fill="url(#paint3_linear_4575_347)"/>
<path d="M5.25 12C5.58997 12 5.92012 12.18 6.11011 12.49C6.29007 12.8 6.29007 13.2 6.11011 13.51C5.92012 13.82 5.58997 14 5.25 14H1.00002C0.910076 14 0.819973 13.9899 0.740013 13.96C0.300059 13.8499 2.31351e-05 13.45 2.31351e-05 13C2.31351e-05 12.55 0.300059 12.1501 0.740013 12.04C0.819973 12.0101 0.910076 12 1.00002 12H5.25Z" fill="url(#paint4_linear_4575_347)"/>
<path d="M2.01003 8C2.34998 8.00003 2.67991 8.18005 2.8699 8.48999C3.0499 8.79999 3.0499 9.20001 2.8699 9.51001C2.67991 9.81995 2.34998 9.99997 2.01003 10H1.00002C0.910061 10 0.819984 9.98994 0.740013 9.95996C0.300043 9.84994 2.31351e-05 9.44998 2.31351e-05 9C2.31351e-05 8.55002 0.300043 8.15006 0.740013 8.04004C0.819984 8.01006 0.910061 8 1.00002 8H2.01003Z" fill="url(#paint5_linear_4575_347)"/>
<path d="M7.63992 8C7.97991 8 8.31002 8.18001 8.50002 8.48999C8.68 8.79998 8.68 9.20002 8.50002 9.51001C8.31002 9.81999 7.97991 10 7.63992 10H5.00002C4.91006 10 4.81998 9.98994 4.74001 9.95996C4.30004 9.84994 4.00002 9.44998 4.00002 9C4.00002 8.55002 4.30004 8.15006 4.74001 8.04004C4.81998 8.01006 4.91006 8 5.00002 8H7.63992Z" fill="url(#paint6_linear_4575_347)"/>
</g>
<defs>
<linearGradient id="paint0_linear_4575_347" x1="11.5687" y1="5.79567" x2="4.90825" y2="18.8817" gradientUnits="userSpaceOnUse">
<stop stop-color="#8B52F4"/>
<stop offset="0.52" stop-color="#7A41DC"/>
<stop offset="0.97" stop-color="#9529C1"/>
</linearGradient>
<radialGradient id="paint1_radial_4575_347" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(8 14.25) rotate(-18.1381) scale(7.6291 11.4609)">
<stop offset="0.307474" stop-color="#7D45E2" stop-opacity="0"/>
<stop offset="0.574002" stop-color="#3B148A"/>
</radialGradient>
<linearGradient id="paint2_linear_4575_347" x1="6.25" y1="-1.25" x2="17.5" y2="12.25" gradientUnits="userSpaceOnUse">
<stop stop-color="#3BD5FF"/>
<stop offset="0.502883" stop-color="#367AF2"/>
<stop offset="0.949445" stop-color="#9C6CFE"/>
</linearGradient>
<linearGradient id="paint3_linear_4575_347" x1="7.49994" y1="8.875" x2="-4.37506" y2="18" gradientUnits="userSpaceOnUse">
<stop offset="0.0244608" stop-color="#367AF2"/>
<stop offset="0.472842" stop-color="#7A41DC"/>
<stop offset="0.804936" stop-color="#9529C1"/>
</linearGradient>
<linearGradient id="paint4_linear_4575_347" x1="7.49994" y1="8.875" x2="-4.37506" y2="18" gradientUnits="userSpaceOnUse">
<stop offset="0.0244608" stop-color="#367AF2"/>
<stop offset="0.472842" stop-color="#7A41DC"/>
<stop offset="0.804936" stop-color="#9529C1"/>
</linearGradient>
<linearGradient id="paint5_linear_4575_347" x1="7.49994" y1="8.875" x2="-4.37506" y2="18" gradientUnits="userSpaceOnUse">
<stop offset="0.0244608" stop-color="#367AF2"/>
<stop offset="0.472842" stop-color="#7A41DC"/>
<stop offset="0.804936" stop-color="#9529C1"/>
</linearGradient>
<linearGradient id="paint6_linear_4575_347" x1="7.49994" y1="8.875" x2="-4.37506" y2="18" gradientUnits="userSpaceOnUse">
<stop offset="0.0244608" stop-color="#367AF2"/>
<stop offset="0.472842" stop-color="#7A41DC"/>
<stop offset="0.804936" stop-color="#9529C1"/>
</linearGradient>
<clipPath id="clip0_4575_347">
<rect width="18" height="18" fill="white"/>
</clipPath>
</defs>
</svg><span>App Service Builder</span></div>
            <nav aria-label="Main navigation">
                <a href="https://builder.appservice.azure.com/docs/">Builder documentation</a>
                <a href="https://github.com/AzureAppService/app-service-builder-php">Source <span aria-hidden="true">&#8599;</span></a>
            </nav>
        </header>
        <main id="main" tabindex="-1">
            <section class="hero" aria-labelledby="greeting-title">
                <div class="intro">
                    <h1 id="greeting-title"><span class="greeting"><?= $escapedGreeting ?></span>This is <em>PHP.</em></h1>
                    <p class="description sans">A small beginning, served by PHP. Explore a dependency-free web app and the App Service Builder GitHub-source workflow.</p>
                    <a class="primary sans" href="https://builder.appservice.azure.com/docs/quickstarts/portal/">Builder quickstart <span aria-hidden="true">&#8599;</span></a>
                </div>
                <aside class="runtime" aria-label="PHP runtime">
                    <div class="runtime-top sans"><span>Server-side, simply.</span><span aria-hidden="true">&lt;?&gt;</span></div>
                    <div class="php-word" aria-hidden="true">php</div>
                    <div class="runtime-bottom sans"><span>Running PHP</span><span class="runtime-version"><?= $escapedVersion ?></span></div>
                </aside>
            </section>
            <section class="response" aria-labelledby="response-title">
                <h2 id="response-title" class="sans">The greeting</h2>
                <pre>App Service Builder / php-web: <?= $escapedGreeting ?> (PHP <?= $escapedVersion ?>)
</pre>
                <button class="sans health-button" type="button" data-health-check data-health-path="/health.php">Check app health</button>
            </section>
            <p id="health-status" class="sans" role="status" aria-live="polite">Checks PHP liveness, not Azure services.</p>
            <section class="details" aria-label="About this sample">
                <div>
                    <h2>Small by design.</h2>
                    <p class="sans">PHP, Composer, and <code>builder.yaml</code> at the repository root. No third-party packages or external services.</p>
                </div>
                <div>
                    <h2>Make it your greeting.</h2>
                    <p class="sans">Set the public <code>GREETING</code> variable in your Builder App, then save and deploy a new version. Unset or empty, it starts with Hello. Never use a secret as your greeting.</p>
                </div>
                <div>
                    <h2>A simple check.</h2>
                    <p class="sans">The <a href="/health.php">health endpoint</a> returns <code>healthy</code>. It stays independent of your greeting.</p>
                </div>
            </section>
        </main>
        <footer class="sans">
            <p>App Service Builder <span aria-hidden="true">/</span> php-web</p>
            <p>For development and testing, not production.</p>
        </footer>
    </div>
    <script>
        const button = document.querySelector('[data-health-check]');
        const status = document.querySelector('#health-status');
        button.addEventListener('click', async () => {
            button.disabled = true;
            status.textContent = 'Checking this app...';
            try {
                const response = await fetch('/health.php', { cache: 'no-store', signal: AbortSignal.timeout(5000) });
                if (!response.ok) throw new Error('HTTP ' + response.status);
                if ((await response.text()).trim() !== 'healthy') throw new Error('Unexpected health response');
                status.textContent = 'Healthy: /health.php returned HTTP 200 and healthy. PHP liveness only.';
            } catch (error) {
                status.textContent = 'Health check failed: ' + error.message + '. Retry the check.';
            } finally {
                button.disabled = false;
            }
        });
    </script>
</body>
</html>
