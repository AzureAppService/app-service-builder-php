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
        .mark { display: grid; place-items: center; width: 44px; height: 44px; color: var(--paper); background: var(--clay); font: italic bold 24px Georgia, serif; }
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
            .mark { flex-shrink: 0; }
        }
    </style>
</head>
<body>
    <a class="skip sans" href="#main">Skip to content</a>
    <div class="page">
        <header class="sans">
            <div class="brand"><span class="mark" aria-hidden="true">p</span><span>App Service Builder</span></div>
            <nav aria-label="Main navigation">
                <a href="https://builder.appservice.azure.com/docs/">Docs</a>
                <a href="https://github.com/AzureAppService/app-service-builder-php">Source <span aria-hidden="true">&#8599;</span></a>
            </nav>
        </header>
        <main id="main" tabindex="-1">
            <section class="hero" aria-labelledby="greeting-title">
                <div class="intro">
                    <h1 id="greeting-title"><span class="greeting"><?= $escapedGreeting ?></span>This is <em>PHP.</em></h1>
                    <p class="description sans">A small beginning, served by PHP. Explore a dependency-free web app and the App Service Builder GitHub-source workflow.</p>
                    <a class="primary sans" href="https://builder.appservice.azure.com/docs/quickstarts/portal/">Explore the quickstart <span aria-hidden="true">&#8599;</span></a>
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
                <a class="sans" href="/health.php">Check health <span aria-hidden="true">&#8599;</span></a>
            </section>
            <section class="details" aria-label="About this sample">
                <div>
                    <h2>Small by design.</h2>
                    <p class="sans">PHP, Composer, and <code>builder.yaml</code> at the repository root. No third-party packages or external services.</p>
                </div>
                <div>
                    <h2>Make it your greeting.</h2>
                    <p class="sans">Set the public <code>GREETING</code> variable to change the message. Unset or empty, it starts with Hello.</p>
                </div>
                <div>
                    <h2>A simple check.</h2>
                    <p class="sans">The <a href="/health.php">health endpoint</a> returns <code>healthy</code>. It stays independent of your greeting.</p>
                </div>
            </section>
        </main>
        <footer class="sans">
            <p>App Service Builder <span aria-hidden="true">/</span> php-web</p>
            <p>Private Preview. For development and testing, not production.</p>
        </footer>
    </div>
</body>
</html>
