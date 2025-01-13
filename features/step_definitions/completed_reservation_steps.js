const { Given, When, Then } = require('@cucumber/cucumber');
const puppeteer = require('puppeteer');

let browser;
let page;

Given('que estoy en la página de inicio', async function () {
  browser = await puppeteer.launch({ headless: false });
  page = await browser.newPage();
  await page.goto('http://localhost:3000/index.php');
});

When('hago clic en el enlace {string}', async function (linkText) {
  await page.click(`a:contains(${linkText})`);
});

When('ingreso {string} en el campo {string}', async function (value, field) {
  await page.type(`input[name=${field}]`, value);
});

When('hago clic en el botón {string}', async function (button) {
  await page.click(`button:contains(${button})`);
});

Then('debería ser redirigido a la página de inicio', async function () {
  await page.waitForNavigation();
  const url = await page.url();
  if (url !== 'http://localhost:3000/index.php') {
    throw new Error(`Expected to be on index page, but was on ${url}`);
  }
});

Then('debería ver una tabla con las reservas', async function () {
  await page.waitForSelector('table#myReservationsTbl');
});

Then('debería ver el mensaje {string}', async function (message) {
  await page.waitForSelector(`body:contains(${message})`);
  const bodyText = await page.evaluate(() => document.body.innerText);
  if (!bodyText.includes(message)) {
    throw new Error(`Expected to see message: ${message}`);
  }
});