const puppeteer = require('puppeteer');
const http = require("http");

async function main (host, port) {
    const browser = await createBrowser();
    const server = http.createServer(async function (req, res) {
        const buffers = [];

        for await (const chunk of req) {
            buffers.push(chunk);
        }

        try {
            const data = JSON.parse(Buffer.concat(buffers).toString());
            const pdf = await render(browser.wsEndpoint(), data.content, data.options);

            res.end(pdf);
        } catch (e) {
            console.error(e);
        }

        res.end();
    });

    server.listen(port, host, () => {
        console.log(`Server is running: http://${host}:${port}`);
    });
}

async function createBrowser() {
    return puppeteer.launch({
        ignoreHTTPSErrors: true,
        headless: true,
        userDataDir: '/tmp/user-data-dir',
        args: ['--disable-gpu', '--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage']
    });
}
async function render(browserWSEndpoint, content, opts = {}) {
    let browser, page;
    try {
        browser = await puppeteer.connect({browserWSEndpoint});

        page = await browser.newPage();
        await page.setJavaScriptEnabled(false);
        await page.setContent(content, {timeout: 5000}).catch((e) => {
            if (!e instanceof puppeteer.TimeoutError) {
                throw e;
            }
        });

        return await page.pdf(opts);
    } catch (e) {
        console.error(e);
    } finally {
        if (page) {
            await page.close();
        }

        if (browser) {
            browser.disconnect();
        }
    }
}

const argv = process.argv.slice(2);

main(argv.at(0), argv.at(1));