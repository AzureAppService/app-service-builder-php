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
THESIS: A language-specific hello-world specimen in the approved soft modern Builder family.
      FORM: Existing sample functionality and honest health/customization behavior retained.
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

body{background:radial-gradient(ellipse at 0 10%,#dceaff,transparent 60%),radial-gradient(ellipse at 100% 90%,#e8e0fa,transparent 60%),#eff3fc;color:#26374f;font-family:"Segoe UI",Arial,sans-serif}.page{max-width:1100px;margin:36px auto;padding:0 35px;background:white;border-radius:23px;box-shadow:0 22px 65px #3b487515}header{padding:27px 0;border-color:#e2e8f1}.hero{padding:40px 0;gap:40px}h1{font-family:inherit;font-size:68px;letter-spacing:-.03em;line-height:1.15}h1 em{color:#676b98;font-style:normal}.greeting{font-family:inherit}.runtime{background:#eeeffb;border-radius:16px;color:#626992;box-shadow:none}.runtime-top,.runtime-bottom{border-color:#d7dcee}.runtime-version{color:#414d70}.php-word{display:grid;place-items:center;font-size:0}.language-logo{width:190px;max-width:100%;height:auto}.primary,.health-button{background:#6269a0;color:#fff;border:0;border-radius:10px;min-height:44px;padding:13px 20px}.primary:hover,.health-button:hover{background:#4f5687}.description,.details p,#health-status{color:#586c85}.response,.details,footer{border-color:#e2e8f1}.response pre{color:#405677;background:#f6f8fd;padding:17px;border-radius:10px;font-size:12px}.response{border-radius:0}.details h2{font-family:inherit;font-weight:600}a:focus-visible,button:focus-visible{outline-color:#7063c5}.runtime-bottom{padding:20px}.brand-logo{width:30px;height:30px}.sample-context{font-size:13px;color:#5d6d88;line-height:1.8;margin:0 0 17px}
@media(max-width:650px){.page{margin:22px 15px;padding:0 21px}.hero{padding:30px 0;gap:28px}h1{font-size:48px}.runtime{min-height:210px}.language-logo{width:170px}.details{gap:24px}header{padding:23px 0}}
header .brand{flex:none}header nav{display:flex;flex-wrap:wrap;gap:20px}@media(max-width:650px){header{flex-direction:column;align-items:flex-start;gap:17px}header nav{justify-content:space-between;width:100%;gap:12px;font-size:11px}.brand{font-size:16px}} </style>
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
            <nav aria-label="Documentation"><a href="https://www.php.net/manual/en/">PHP documentation</a>
                <a href="https://builder.appservice.azure.com/docs/">Builder documentation</a>
                <a href="https://github.com/AzureAppService/app-service-builder-php">Source <span aria-hidden="true">&#8599;</span></a>
            </nav>
        </header>
        <main id="main" tabindex="-1">
            <section class="hero" aria-labelledby="greeting-title">
                <div class="intro">
                    <p class="sample-context">PHP sample for App Service Builder</p><h1 id="greeting-title"><span class="greeting"><?= $escapedGreeting ?></span>This is <em>PHP.</em></h1>
                    <p class="description sans">A small beginning, served by PHP. Explore a dependency-free web app and the App Service Builder GitHub-source workflow.</p>
                    <a class="primary sans" href="https://builder.appservice.azure.com/docs/quickstarts/portal/">Builder quickstart <span aria-hidden="true">&#8599;</span></a>
                </div>
                <aside class="runtime" aria-label="PHP runtime">
                    <div class="runtime-top sans"><span>Server-side, simply.</span><span aria-hidden="true">&lt;?&gt;</span></div>
                    <div class="php-word"><svg class="language-logo" role="img" aria-label="PHP" height="383.5975" id="php-svg3430" version="1.1" viewBox="0 0 711.20123 383.5975" width="711.20123" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg">
  <title id="php-title3510">Official PHP Logo</title>
  <metadata id="php-metadata3436">
    <rdf:RDF>
      <cc:Work rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/>
        <dc:title>Official PHP Logo</dc:title>
        <dc:creator>
          <cc:Agent>
            <dc:title>Colin Viebrock</dc:title>
          </cc:Agent>
        </dc:creator>
        <dc:description/>
        <dc:contributor>
          <cc:Agent>
            <dc:title/>
          </cc:Agent>
        </dc:contributor>
        <cc:license rdf:resource="http://creativecommons.org/licenses/by-sa/4.0/"/>
        <dc:rights>
          <cc:Agent>
            <dc:title>Copyright Colin Viebrock 1997 - All rights reserved.</dc:title>
          </cc:Agent>
        </dc:rights>
        <dc:date>1997</dc:date>
      </cc:Work>
      <cc:License rdf:about="http://creativecommons.org/licenses/by-sa/4.0/">
        <cc:permits rdf:resource="http://creativecommons.org/ns#Reproduction"/>
        <cc:permits rdf:resource="http://creativecommons.org/ns#Distribution"/>
        <cc:requires rdf:resource="http://creativecommons.org/ns#Notice"/>
        <cc:requires rdf:resource="http://creativecommons.org/ns#Attribution"/>
        <cc:permits rdf:resource="http://creativecommons.org/ns#DerivativeWorks"/>
        <cc:requires rdf:resource="http://creativecommons.org/ns#ShareAlike"/>
      </cc:License>
    </rdf:RDF>
  </metadata>
  <defs id="php-defs3434">
    <clipPath clipPathUnits="userSpaceOnUse" id="php-clipPath3444">
      <path d="M 11.52,162 C 11.52,81.677 135.307,16.561 288,16.561 l 0,0 c 152.693,0 276.481,65.116 276.481,145.439 l 0,0 c 0,80.322 -123.788,145.439 -276.481,145.439 l 0,0 C 135.307,307.439 11.52,242.322 11.52,162" id="php-path3446"/>
    </clipPath>
    <radialGradient cx="0" cy="0" fx="0" fy="0" gradientTransform="matrix(363.05789,0,0,-363.05789,177.52002,256.30713)" gradientUnits="userSpaceOnUse" id="php-radialGradient3452" r="1" spreadMethod="pad">
      <stop id="php-stop3454" offset="0" style="stop-opacity:1;stop-color:#aeb2d5"/>
      <stop id="php-stop3456" offset="0.3" style="stop-opacity:1;stop-color:#aeb2d5"/>
      <stop id="php-stop3458" offset="0.75" style="stop-opacity:1;stop-color:#484c89"/>
      <stop id="php-stop3460" offset="1" style="stop-opacity:1;stop-color:#484c89"/>
    </radialGradient>
    <clipPath clipPathUnits="userSpaceOnUse" id="php-clipPath3468">
      <path d="M 0,324 576,324 576,0 0,0 0,324 Z" id="php-path3470"/>
    </clipPath>
    <clipPath clipPathUnits="userSpaceOnUse" id="php-clipPath3480">
      <path d="M 0,324 576,324 576,0 0,0 0,324 Z" id="php-path3482"/>
    </clipPath>
  </defs>
  <g id="php-g3438" transform="matrix(1.25,0,0,-1.25,-4.4,394.29875)">
    <g id="php-g3440">
      <g clip-path="url(#php-clipPath3444)" id="php-g3442">
        <g id="php-g3448">
          <g id="php-g3450">
            <path d="M 11.52,162 C 11.52,81.677 135.307,16.561 288,16.561 l 0,0 c 152.693,0 276.481,65.116 276.481,145.439 l 0,0 c 0,80.322 -123.788,145.439 -276.481,145.439 l 0,0 C 135.307,307.439 11.52,242.322 11.52,162" id="php-path3462" style="fill:url(#php-radialGradient3452);stroke:none"/>
          </g>
        </g>
      </g>
    </g>
    <g id="php-g3464">
      <g clip-path="url(#php-clipPath3468)" id="php-g3466">
        <g id="php-g3472" transform="translate(288,27.3594)">
          <path d="M 0,0 C 146.729,0 265.68,60.281 265.68,134.641 265.68,209 146.729,269.282 0,269.282 -146.729,269.282 -265.68,209 -265.68,134.641 -265.68,60.281 -146.729,0 0,0" id="php-path3474" style="fill:#777bb3;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
      </g>
    </g>
    <g id="php-g3476">
      <g clip-path="url(#php-clipPath3480)" id="php-g3478">
        <g id="php-g3484" transform="translate(161.7344,145.3066)">
          <path d="m 0,0 c 12.065,0 21.072,2.225 26.771,6.611 5.638,4.341 9.532,11.862 11.573,22.353 1.903,9.806 1.178,16.653 -2.154,20.348 C 32.783,53.086 25.417,55 14.297,55 L -4.984,55 -15.673,0 0,0 Z m -63.063,-67.75 c -0.895,0 -1.745,0.4 -2.314,1.092 -0.57,0.691 -0.801,1.601 -0.63,2.48 L -37.679,81.573 C -37.405,82.982 -36.17,84 -34.734,84 L 26.32,84 C 45.508,84 59.79,78.79 68.767,68.513 77.792,58.182 80.579,43.741 77.05,25.592 75.614,18.198 73.144,11.331 69.709,5.183 66.27,-0.972 61.725,-6.667 56.198,-11.747 49.582,-17.939 42.094,-22.429 33.962,-25.071 25.959,-27.678 15.681,-29 3.414,-29 l -24.722,0 -7.06,-36.322 c -0.274,-1.41 -1.508,-2.428 -2.944,-2.428 l -31.751,0 z" id="php-path3486" style="fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
        <g id="php-g3488" transform="translate(159.2236,197.3071)">
          <path d="m 0,0 16.808,0 c 13.421,0 18.083,-2.945 19.667,-4.7 2.628,-2.914 3.124,-9.058 1.435,-17.767 C 36.012,-32.217 32.494,-39.13 27.452,-43.012 22.29,-46.986 13.898,-49 2.511,-49 L -9.523,-49 0,0 Z m 28.831,35 -61.055,0 c -2.872,0 -5.341,-2.036 -5.889,-4.855 l -28.328,-145.751 c -0.342,-1.759 0.12,-3.578 1.259,-4.961 1.14,-1.383 2.838,-2.183 4.63,-2.183 l 31.75,0 c 2.873,0 5.342,2.036 5.89,4.855 l 6.588,33.895 22.249,0 c 12.582,0 23.174,1.372 31.479,4.077 8.541,2.775 16.399,7.48 23.354,13.984 5.752,5.292 10.49,11.232 14.08,17.657 3.591,6.427 6.171,13.594 7.668,21.302 3.715,19.104 0.697,34.402 -8.969,45.466 C 63.965,29.444 48.923,35 28.831,35 m -45.633,-90 19.313,0 c 12.801,0 22.336,2.411 28.601,7.234 6.266,4.824 10.492,12.875 12.688,24.157 2.101,10.832 1.144,18.476 -2.871,22.929 C 36.909,3.773 28.87,6 16.808,6 L -4.946,6 -16.802,-55 M 28.831,29 C 47.198,29 60.597,24.18 69.019,14.539 77.44,4.898 79.976,-8.559 76.616,-25.836 75.233,-32.953 72.894,-39.46 69.601,-45.355 66.304,-51.254 61.999,-56.648 56.679,-61.539 50.339,-67.472 43.296,-71.7 35.546,-74.218 27.796,-76.743 17.925,-78 5.925,-78 l -27.196,0 -7.531,-38.75 -31.75,0 28.328,145.75 61.055,0" id="php-path3490" style="fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
        <g id="php-g3492" transform="translate(311.583,116.3066)">
          <path d="m 0,0 c -0.896,0 -1.745,0.4 -2.314,1.092 -0.571,0.691 -0.802,1.6 -0.631,2.48 L 9.586,68.061 C 10.778,74.194 10.484,78.596 8.759,80.456 7.703,81.593 4.531,83.5 -4.848,83.5 L -27.55,83.5 -43.305,2.428 C -43.579,1.018 -44.814,0 -46.25,0 l -31.5,0 c -0.896,0 -1.745,0.4 -2.315,1.092 -0.57,0.691 -0.801,1.601 -0.63,2.48 l 28.328,145.751 c 0.274,1.409 1.509,2.427 2.945,2.427 l 31.5,0 c 0.896,0 1.745,-0.4 2.315,-1.091 0.57,-0.692 0.801,-1.601 0.63,-2.481 L -21.813,113 2.609,113 c 18.605,0 31.221,-3.28 38.569,-10.028 7.49,-6.884 9.827,-17.891 6.947,-32.719 L 34.945,2.428 C 34.671,1.018 33.437,0 32,0 L 0,0 Z" id="php-path3494" style="fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
        <g id="php-g3496" transform="translate(293.6611,271.0571)">
          <path d="m 0,0 -31.5,0 c -2.873,0 -5.342,-2.036 -5.89,-4.855 l -28.328,-145.751 c -0.342,-1.759 0.12,-3.578 1.26,-4.961 1.14,-1.383 2.838,-2.183 4.63,-2.183 l 31.5,0 c 2.872,0 5.342,2.036 5.89,4.855 l 15.283,78.645 20.229,0 c 9.363,0 11.328,-2 11.407,-2.086 0.568,-0.611 1.315,-3.441 0.082,-9.781 l -12.531,-64.489 c -0.342,-1.759 0.12,-3.578 1.26,-4.961 1.14,-1.383 2.838,-2.183 4.63,-2.183 l 32,0 c 2.872,0 5.342,2.036 5.89,4.855 l 13.179,67.825 c 3.093,15.921 0.447,27.864 -7.861,35.5 -7.928,7.281 -21.208,10.82 -40.599,10.82 l -20.784,0 6.143,31.605 C 6.231,-5.386 5.77,-3.566 4.63,-2.184 3.49,-0.801 1.792,0 0,0 m 0,-6 -7.531,-38.75 28.062,0 c 17.657,0 29.836,-3.082 36.539,-9.238 6.703,-6.16 8.711,-16.141 6.032,-29.938 l -13.18,-67.824 -32,0 12.531,64.488 c 1.426,7.336 0.902,12.34 -1.574,15.008 -2.477,2.668 -7.746,4.004 -15.805,4.004 l -25.176,0 -16.226,-83.5 -31.5,0 L -31.5,-6 0,-6" id="php-path3498" style="fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
        <g id="php-g3500" transform="translate(409.5498,145.3066)">
          <path d="m 0,0 c 12.065,0 21.072,2.225 26.771,6.611 5.638,4.34 9.532,11.861 11.574,22.353 1.903,9.806 1.178,16.653 -2.155,20.348 C 32.783,53.086 25.417,55 14.297,55 L -4.984,55 -15.673,0 0,0 Z m -63.062,-67.75 c -0.895,0 -1.745,0.4 -2.314,1.092 -0.57,0.691 -0.802,1.601 -0.631,2.48 L -37.679,81.573 C -37.404,82.982 -36.17,84 -34.733,84 L 26.32,84 C 45.509,84 59.79,78.79 68.768,68.513 77.793,58.183 80.579,43.742 77.051,25.592 75.613,18.198 73.144,11.331 69.709,5.183 66.27,-0.972 61.725,-6.667 56.198,-11.747 49.582,-17.939 42.094,-22.429 33.962,-25.071 25.959,-27.678 15.681,-29 3.414,-29 l -24.723,0 -7.057,-36.322 c -0.275,-1.41 -1.509,-2.428 -2.946,-2.428 l -31.75,0 z" id="php-path3502" style="fill:#000000;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
        <g id="php-g3504" transform="translate(407.0391,197.3071)">
          <path d="M 0,0 16.808,0 C 30.229,0 34.891,-2.945 36.475,-4.7 39.104,-7.614 39.6,-13.758 37.91,-22.466 36.012,-32.217 32.493,-39.13 27.452,-43.012 22.29,-46.986 13.898,-49 2.511,-49 L -9.522,-49 0,0 Z m 28.831,35 -61.054,0 c -2.872,0 -5.341,-2.036 -5.889,-4.855 L -66.44,-115.606 c -0.342,-1.759 0.12,-3.578 1.259,-4.961 1.14,-1.383 2.838,-2.183 4.63,-2.183 l 31.75,0 c 2.872,0 5.342,2.036 5.89,4.855 l 6.587,33.895 22.249,0 c 12.582,0 23.174,1.372 31.479,4.077 8.541,2.775 16.401,7.481 23.356,13.986 5.752,5.291 10.488,11.23 14.078,17.655 3.591,6.427 6.171,13.594 7.668,21.302 3.715,19.105 0.697,34.403 -8.969,45.467 C 63.965,29.444 48.924,35 28.831,35 m -45.632,-90 19.312,0 c 12.801,0 22.336,2.411 28.601,7.234 6.267,4.824 10.492,12.875 12.688,24.157 2.102,10.832 1.145,18.476 -2.871,22.929 C 36.909,3.773 28.87,6 16.808,6 L -4.946,6 -16.801,-55 M 28.831,29 C 47.198,29 60.597,24.18 69.019,14.539 77.441,4.898 79.976,-8.559 76.616,-25.836 75.233,-32.953 72.894,-39.46 69.601,-45.355 66.304,-51.254 61.999,-56.648 56.679,-61.539 50.339,-67.472 43.296,-71.7 35.546,-74.218 27.796,-76.743 17.925,-78 5.925,-78 l -27.196,0 -7.53,-38.75 -31.75,0 28.328,145.75 61.054,0" id="php-path3506" style="fill:#ffffff;fill-opacity:1;fill-rule:nonzero;stroke:none"/>
        </g>
      </g>
    </g>
  </g>
</svg></div>
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
        <p>PHP logo from <a href="https://www.php.net/download-logos.php">the official project</a>; used to identify this sample’s technology.</p></footer>
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
