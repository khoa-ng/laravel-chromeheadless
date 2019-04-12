<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HeadlessChromium\BrowserFactory;

class scrapeTest extends Controller
{
    public function index(Request $request) {
        set_time_limit(FALSE);
        $browserFactory = new BrowserFactory('/usr/bin/chromium-browser');

        // starts headless chrome
        $browser = $browserFactory->createBrowser([
            'headless'        => true,         // disable headless mode
            'connectionDelay' => 0.8,           // add 0.8 second of delay between each instruction sent to chrome,
            'debugLogger'     => 'php://stdout', // will enable verbose mode
            'customFlags' => [
                '--no-sandbox'
            ],
            'noSandbox' => true,
            'startupTimeout' => 300000,
            'sendSyncDefaultTimeout' => 3000000
        ]);

        // creates a new page and navigate to an url
        $page = $browser->createPage();
        $page->navigate('https://www.google.com')->waitForNavigation();
        
        // get page title
        $pageTitle = $page->evaluate('document.title')->getReturnValue();
        
        // screenshot - Say "Cheese"! 😄
        $page->screenshot()->saveToFile('/media/Workspace/khoa/natalia/pdf.png');
        
        // pdf
        $page->pdf(['printBackground'=>false])->saveToFile('/media/Workspace/khoa/natalia/pdf.pdf');
        
        // bye
        $browser->close();
    }
}
