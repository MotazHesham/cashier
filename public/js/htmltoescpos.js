import { closeBrowser, CreateNetworkPrinter } from "../../node_modules/htmltoescpos";

const test = async () => {
    const printer = CreateNetworkPrinter("192.168.0.151");
    await printer.printUrl("https://www.google.com", {
        beep: { count: 2, time: 5 }
    });
    await closeBrowser();
};

test();
