import json
import os
import re
import selectors
import subprocess
import unittest
from contextlib import contextmanager
from html.parser import HTMLParser
from http.client import HTTPConnection
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
PHP = os.environ.get("PHP_BIN", "php")

class DocumentationTests(unittest.TestCase):
    def test_readme_docs_host(self):
        readme = (ROOT / "README.md").read_text(encoding="utf-8")
        self.assertIn("https://builder.appservice.azure.com/docs/", readme)
        self.assertNotIn("https://builderapps.azure.com/docs", readme)


class HomeParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.greeting = []
        self.in_greeting = False

    def handle_starttag(self, tag, attrs):
        if tag == "span" and ("class", "greeting") in attrs:
            self.in_greeting = True

    def handle_endtag(self, tag):
        if tag == "span":
            self.in_greeting = False

    def handle_data(self, data):
        if self.in_greeting:
            self.greeting.append(data)


@contextmanager
def development_server(greeting=None):
    environment = os.environ.copy()
    environment.pop("GREETING", None)
    if greeting is not None:
        environment["GREETING"] = greeting
    environment.pop("PHP_CLI_SERVER_WORKERS", None)
    environment["UNRELATED_SAMPLE_SETTING"] = "not-part-of-the-response"
    process = subprocess.Popen(
        [PHP, "-S", "127.0.0.1:0", "-t", str(ROOT)],
        cwd=ROOT,
        env=environment,
        stdout=subprocess.DEVNULL,
        stderr=subprocess.PIPE,
        text=True,
    )
    try:
        with selectors.DefaultSelector() as selector:
            selector.register(process.stderr, selectors.EVENT_READ)
            if not selector.select(timeout=5):
                raise RuntimeError("PHP development server did not announce startup")
            startup = process.stderr.readline()
        address = re.search(r"http://127\.0\.0\.1:(\d+)\) started", startup)
        if address is None or process.poll() is not None:
            raise RuntimeError(f"PHP development server failed to start: {startup}")
        yield process, int(address.group(1))
    finally:
        if process.poll() is None:
            process.terminate()
        try:
            process.wait(timeout=5)
        except subprocess.TimeoutExpired:
            process.kill()
            process.wait(timeout=5)
            raise RuntimeError("PHP development server did not stop after termination")
        finally:
            process.stderr.close()


class HTTPTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.version = subprocess.check_output(
            [PHP, "-r", "echo PHP_VERSION;"], text=True
        )
        if not cls.version.startswith("8.5."):
            raise RuntimeError(f"PHP 8.5.x is required; found {cls.version}")

    def assert_response(self, server, path, status, body, method="GET"):
        process, port = server
        self.assertIsNone(process.poll(), "Owned PHP server stopped unexpectedly")
        connection = HTTPConnection("127.0.0.1", port, timeout=5)
        try:
            connection.request(method, path)
            response = connection.getresponse()
            content = response.read()
            self.assertEqual(response.status, status)
            self.assertEqual(content, body.encode("utf-8"))
            self.assertEqual(
                response.getheader("Content-Type"), "text/plain; charset=utf-8"
            )
        finally:
            connection.close()
        self.assertIsNone(process.poll(), "Owned PHP server stopped unexpectedly")

    def test_unset_greeting(self):
        with development_server() as server:
            self.assert_home(server, "/", "Hello")

    def test_direct_index(self):
        with development_server() as server:
            self.assert_home(server, "/index.php", "Hello")

    def test_exact_health_body(self):
        with development_server() as server:
            self.assert_response(server, "/health.php", 200, "healthy\n")

    def test_custom_greeting_does_not_change_health(self):
        with development_server("Welcome") as server:
            self.assert_home(server, "/", "Welcome")
            self.assert_response(server, "/health.php", 200, "healthy\n")

    def test_empty_greeting_uses_default(self):
        with development_server("") as server:
            self.assert_home(server, "/", "Hello")

    def test_zero_greeting_is_not_empty(self):
        with development_server("0") as server:
            self.assert_home(server, "/", "0")

    def test_whitespace_greeting_is_preserved(self):
        with development_server(" ") as server:
            self.assert_home(server, "/", " ")

    def test_utf8_greeting_is_preserved_in_html(self):
        with development_server("Caf\u00e9 <sample>") as server:
            self.assert_home(server, "/", "Caf\u00e9 <sample>")

    def test_query_does_not_change_response(self):
        with development_server() as server:
            self.assert_home(server, "/?GREETING=ignored", "Hello")
            self.assert_home(server, "/index.php?GREETING=ignored", "Hello")
            self.assert_response(server, "/health.php?check=1", 200, "healthy\n")

    def test_unknown_path(self):
        with development_server() as server:
            self.assert_response(server, "/not-found", 404, "Not found\n")

    def test_health_head(self):
        with development_server() as server:
            self.assert_response(server, "/health.php", 200, "", method="HEAD")

    def assert_home(self, server, path, greeting, method="GET"):
        process, port = server
        self.assertIsNone(process.poll())
        connection = HTTPConnection("127.0.0.1", port, timeout=5)
        try:
            connection.request(method, path)
            response = connection.getresponse()
            self.assertEqual(response.status, 200)
            self.assertEqual(
                response.getheader("Content-Type"), "text/html; charset=utf-8"
            )
            self.assertIsNone(response.getheader("Location"))
            content = response.read().decode("utf-8")
            if method == "GET":
                self.assertIn('href="https://builder.appservice.azure.com/docs/"', content)
                self.assertIn('href="https://builder.appservice.azure.com/docs/quickstarts/portal/"', content)
                self.assertNotIn("https://builderapps.azure.com/docs", content)
        finally:
            connection.close()
        if method == "HEAD":
            self.assertEqual(content, "")
            return
        parser = HomeParser()
        parser.feed(content)
        self.assertEqual("".join(parser.greeting), greeting)
        self.assertIn(f'<span class="runtime-version">{self.version}</span>', content)
        self.assertIn('<html lang="en">', content)
        self.assertIn('id="greeting-title"', content)
        self.assertIn('href="/health.php"', content)
        self.assertNotIn("View plain text", content)
        self.assertNotIn("/demo", content)
        self.assertNotIn("not-part-of-the-response", content)
        return content

    def test_home_routes(self):
        with development_server() as server:
            for path in ("/", "/index.php", "/?scoutTheme=light"):
                with self.subTest(path=path):
                    self.assert_home(server, path, "Hello")

    def test_home_greeting_configuration(self):
        for value, expected in ((None, "Hello"), ("", "Hello"), ("Welcome", "Welcome"),
                                ("0", "0"), (" ", " ")):
            with self.subTest(greeting=value), development_server(value) as server:
                self.assert_home(server, "/", expected)
                self.assert_home(server, "/index.php", expected)
                self.assert_response(server, "/health.php", 200, "healthy\n")

    def test_home_escapes_public_greeting(self):
        greeting = 'Caf\u00e9 <script>alert("sample")</script> & \'friends\''
        with development_server(greeting) as server:
            content = self.assert_home(server, "/", greeting)
            self.assertNotIn("<script>", content)
            self.assertIn("&lt;script&gt;", content)
            self.assertIn("&amp;", content)
            self.assertIn("&quot;", content)
            self.assertIn("&#039;", content)

    def test_home_head_and_post(self):
        with development_server() as server:
            self.assert_home(server, "/", "Hello", method="HEAD")
            self.assert_home(server, "/index.php", "Hello", method="HEAD")
            self.assert_home(server, "/", "Hello", method="POST")

    def test_removed_routes(self):
        with development_server() as server:
            for path in ("/demo", "/demo/", "/demo/index.php",
                         "/demo?scoutTheme=light", "/demo/not-found",
                         "/index.php/not-found"):
                with self.subTest(path=path):
                    self.assert_response(server, path, 404, "Not found\n")
            self.assert_response(server, "/demo", 404, "Not found\n", method="POST")
            self.assert_response(server, "/demo", 404, "", method="HEAD")


class SourceContractTests(unittest.TestCase):
    def test_minimal_hosted_manifest(self):
        self.assertEqual(
            (ROOT / "builder.yaml").read_text(),
            'components:\n'
            '  - name: web\n'
            '    role: web\n'
            '    platform: php\n'
            '    platformVersion: "8.5"\n'
            '    run:\n'
            '      healthCheckPath: /health.php\n',
        )

    def test_dependency_free_composer_project(self):
        project = json.loads((ROOT / "composer.json").read_text())
        self.assertEqual(project["require"], {"php": ">=8.5 <8.6"})
        self.assertEqual(project.get("require-dev", {}), {})
        lock = json.loads((ROOT / "composer.lock").read_text())
        self.assertEqual(lock["packages"], [])
        self.assertEqual(lock["packages-dev"], [])

    def test_only_sample_php_endpoints(self):
        endpoints = {
            path.relative_to(ROOT).as_posix()
            for path in ROOT.rglob("*.php")
            if not path.is_relative_to(ROOT / "vendor")
        }
        self.assertEqual(endpoints, {"index.php", "health.php"})
        self.assertFalse((ROOT / "demo").exists())


if __name__ == "__main__":
    unittest.main()
