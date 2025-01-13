const { Given, When, Then } = require('@cucumber/cucumber');
const puppeteer = require('puppeteer');

let browser;
let page;

Given('que estoy en la página de reservas', async function () {
  browser = await puppeteer.launch({ headless: false });
  page = await browser.newPage();
  await page.goto('http://localhost:3000/reservation.php');
});

When('ingreso {string} en el campo {string}', async function (value, field) {
  await page.type(`input[name=${field}]`, value);
});

When('hago clic en el botón {string}', async function (button) {
  await page.click(`button:contains(${button})`);
});

Then('debería ser redirigido a la página de reservas completadas', async function () {
  await page.waitForNavigation();
  const url = await page.url();
  if (url !== 'http://localhost:3000/completed-reservations.php') {
    throw new Error(`Expected to be on completed reservations page, but was on ${url}`);
  }
});

Then('debería ver el texto {string}', async function (text) {
  await page.waitForSelector(`body:contains(${text})`);
});

Then('debería ver una tabla con las reservas', async function () {
  await page.waitForSelector('table#myReservationsTbl');
});

Then('debería ser redirigido a la página de inicio de sesión', async function () {
  await page.waitForNavigation();
  const url = await page.url();
  if (url !== 'http://localhost:3000/sign-in.php') {
    throw new Error(`Expected to be on sign-in page, but was on ${url}`);
  }
});