// This instantiates a keydown event listener to provide a basic user input.

if (typeof KeyEvent === "undefined") {
    var KeyEvent = {
        DOM_VK_1: 49,
        DOM_VK_2: 50,
        DOM_VK_3: 51,
        DOM_VK_4: 52,
        DOM_VK_5: 53
    };
}

addEventListener("keydown", function (e) {
    // Range validation
    if (KeyEvent.DOM_VK_1 <= e.keyCode && KeyEvent.DOM_VK_5 >= e.keyCode) {
        // Put the new URL to be the choice: keycode - minimum_possible_keycode.
        var newUrl = window.location.href;
        // Determine if this is the start page or not; if so, let's not chop off rightmost segment.
        if (!isNaN(newUrl.charAt(newUrl.lastIndexOf('/') + 1))) {
            newUrl = newUrl.substr(0, newUrl.lastIndexOf('/'));
        }
        newUrl += '/';
        window.location.href = newUrl + (e.keyCode - KeyEvent.DOM_VK_1);
    }
});
