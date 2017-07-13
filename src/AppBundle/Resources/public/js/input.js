// This instantiates a keydown event listener to provide a basic user input.
// Some versions of Chrome lack KeyEvent VK_ definitions, so we check that here and accommodate it.
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
    // Perform range validation against key pressed to only allow "1" - "5" keys
    if (KeyEvent.DOM_VK_1 <= e.keyCode && KeyEvent.DOM_VK_5 >= e.keyCode) {
        // Here, we are going to finagle the current URL.  While slightly complicated, this keeps us location-agnostic.
        // Append the choice to the new URL, ergo "1" key becomes <url>/0, "4" becomes <url>/3, etc.
        // This last segment is fed into the controller as the input parameter representing the user's choice
        // in the game and evaluated against a random computer choice.
        var newUrl = window.location.href;
        // Determine if this URL is the start page or not as dictated by the absence of a number as the last segment.
        // If so, let's not chop off the rightmost segment.
        if (!isNaN(newUrl.charAt(newUrl.lastIndexOf('/') + 1))) {
            newUrl = newUrl.substr(0, newUrl.lastIndexOf('/'));
        }
        newUrl += '/';
        // Redirect to the page which will invoke the controller.
        window.location.href = newUrl + (e.keyCode - KeyEvent.DOM_VK_1);
    }
});
