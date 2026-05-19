import puppeteer from 'puppeteer-core';
import path from 'path';

(async () => {
  try {
    console.log('Launching browser...');
    const browser = await puppeteer.launch({
      executablePath: 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
      headless: true,
      defaultViewport: { width: 1440, height: 900 }
    });

    const page = await browser.newPage();

    // 1. Log in
    console.log('Navigating to login page...');
    await page.goto('http://127.0.0.1:8000/login', { waitUntil: 'networkidle2' });

    console.log('Logging in as admin...');
    await page.type('input[type="email"]', 'admin@pawmart.com');
    await page.type('input[type="password"]', 'password');
    
    // Click submit and wait for navigation
    await Promise.all([
      page.waitForNavigation({ waitUntil: 'networkidle2' }),
      page.click('button[type="submit"]')
    ]);

    console.log('Login successful.');

    // 2. Admin Dashboard - Empirical Analytics
    console.log('Navigating to Admin Dashboard...');
    await page.goto('http://127.0.0.1:8000/admin/dashboard', { waitUntil: 'networkidle2' });
    
    // Scroll down to the empirical analytics card to capture it
    await page.evaluate(() => {
      window.scrollTo(0, document.body.scrollHeight);
    });
    // Wait a brief moment for scroll and rendering
    await new Promise(r => setTimeout(r, 1000));

    console.log('Saving empirical_analytics.png...');
    await page.screenshot({
      path: 'artifacts/screenshots/empirical_analytics.png',
      fullPage: true
    });

    // 3. Admin Products - Balanced Product BST Search
    console.log('Navigating to Admin Products...');
    await page.goto('http://127.0.0.1:8000/admin/products', { waitUntil: 'networkidle2' });
    
    // Search for a product (e.g. type 'd' to filter or search)
    console.log('Performing BST search...');
    await page.type('input[name="search"]', 'Dog');
    
    await Promise.all([
      page.waitForNavigation({ waitUntil: 'networkidle2' }),
      page.click('button[type="submit"]')
    ]);

    await new Promise(r => setTimeout(r, 1000));
    console.log('Saving bst_search.png...');
    await page.screenshot({
      path: 'artifacts/screenshots/bst_search.png',
      fullPage: true
    });

    // 4. Admin Orders - Heap Priority Order Queue
    console.log('Navigating to Admin Orders...');
    // We navigate to orders index which will show the heap scheduler by default
    await page.goto('http://127.0.0.1:8000/admin/orders?schedule=priority', { waitUntil: 'networkidle2' });
    
    await new Promise(r => setTimeout(r, 1000));
    console.log('Saving heap_scheduler.png...');
    await page.screenshot({
      path: 'artifacts/screenshots/heap_scheduler.png',
      fullPage: true
    });

    console.log('Closing browser...');
    await browser.close();
    console.log('Screenshots generated successfully!');
  } catch (error) {
    console.error('Error generating screenshots:', error);
    process.exit(1);
  }
})();
